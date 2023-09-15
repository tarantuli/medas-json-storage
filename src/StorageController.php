<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\Core\Attributes\Service;
use Medas\Core\Interfaces\Serializer as SerializerInterface;
use Medas\JsonStorage\Builders\MigrationBuilder;
use Medas\JsonStorage\IO\PathBuilder;
use Medas\JsonStorage\Transactions\Transaction;
use Medas\JsonStorage\Transactions\TransactionCollection;
use Medas\StorageManager\Interfaces\{ActionBuilders,
    ActionExecutor,
    RecordFetchers as RecordFetchersInterface,
    Storage,
    StorageController as StorageControllerInterface,
    Store,
    Transaction as TransactionInterface};
use Medas\StorageManager\Migrations\MigrationBuilder as MigrationBuilderInterface;

#[Service]
class StorageController implements StorageControllerInterface
{
    private StorageDirectory $defaultStorage;
    private readonly FileCollection $fileCollection;
    private readonly TransactionCollection $transactionCollection;

    public function __construct(
        private readonly Serializer  $serializer,
        private readonly PathBuilder $pathBuilder,
    )
    {
        $this->fileCollection = new FileCollection();
        $this->transactionCollection = new TransactionCollection();
    }

    public function handles(Storage $storage): bool
    {
        /** @noinspection PhpConditionAlreadyCheckedInspection */
        if ($storage instanceof StorageDirectory) {
            if (!isset($this->defaultStorage)) {
                $this->defaultStorage = $storage;
            }

            return true;
        }

        return false;
    }

    public function store(string $name, Storage $storage = null): Store
    {
        return $this->fileCollection->get($storage ?? $this->defaultStorage, $name);
    }

    public function deleteStore(Store $store): void
    {
        $storage ??= $this->defaultStorage;

        $path = $this->pathBuilder->build($storage, $store->name());

        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function transaction(Storage $storage = null): TransactionInterface
    {
        $id = spl_object_id($storage);

        if (!$this->transactionCollection->offsetExists($id)) {
            $this->transactionCollection->offsetSet($id, new Transaction());
        }

        return $this->transactionCollection->offsetGet($id);
    }

    public function lastGeneratedValue(Storage $storage = null): int|null
    {
        // $storage ??= $this->defaultStorage;
        // TODO: Implement lastGeneratedValue() method.
    }

    public function serializer(Storage $storage = null): SerializerInterface
    {
        return $this->serializer;
    }

    public function actionBuilders(): ActionBuilders
    {
        return service(ActionBuilders::class);
    }

    public function actionExecutor(): ActionExecutor
    {
        return service(Actions\Executor::class);
    }

    public function recordFetchers(): RecordFetchersInterface
    {
        return service(RecordFetchers::class);
    }

    public function migrationBuilder(): MigrationBuilderInterface
    {
        return service(MigrationBuilder::class);
    }

    public function hasStore(Store $store, Storage $storage = null): bool
    {
        $storage ??= $this->defaultStorage;

        $path = $this->pathBuilder->build($storage, $store->name());

        return file_exists($path);
    }
}
