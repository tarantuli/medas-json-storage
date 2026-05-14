<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\Core\{Attributes\Service, Interfaces\Serializer as SerializerInterface};
use Medas\StorageManager\{
    Interfaces\ActionBuilders,
    Interfaces\ActionExecutor,
    Interfaces\RecordFetchers as RecordFetchersInterface,
    Interfaces\Storage,
    Interfaces\StorageController as StorageControllerInterface,
    Interfaces\Store,
    Interfaces\Transaction as TransactionInterface,
    Shared\ValueSerializer
};

#[Service]
class StorageController implements StorageControllerInterface
{
    private StorageDirectory $defaultStorage;
    private readonly FileCollection $fileCollection;
    private readonly Transactions\TransactionCollection $transactionCollection;

    public function __construct(
        private readonly Data\DataManager $dataManager,
        private readonly IO\PathBuilder   $pathBuilder,
        private readonly ValueSerializer  $serializer,
    )
    {
        $this->fileCollection = new FileCollection();
        $this->transactionCollection = new Transactions\TransactionCollection();
    }

    public function handles(Storage $storage): bool
    {
        return $storage instanceof StorageDirectory;
    }

    public function initialize(Storage $storage): void
    {
        /** @var StorageDirectory $storage */
        if (!isset($this->defaultStorage)) {
            $this->defaultStorage = $storage;
        }
    }

    public function store(string $name, Storage|null $storage = null): Store
    {
        return $this->fileCollection->get($storage ?? $this->defaultStorage, $name);
    }

    public function getStores(Storage|null $storage = null, string|null $nameFilter = null): array
    {
        // $storage ??= $this->defaultStorage;
        // TODO: Implement getStores() method.
        return [];
    }

    public function deleteStore(Store $store): void
    {
        /** @var StorageFile $store */
        $storage ??= $this->defaultStorage;
        $path = $this->pathBuilder->build($storage, $store->name());

        if (file_exists($path)) {
            unlink($path);
        }

        $this->dataManager->delete($store);
    }

    public function transaction(Storage|null $storage = null): TransactionInterface
    {
        $id = spl_object_id($storage);

        if (!$this->transactionCollection->offsetExists($id)) {
            $this->transactionCollection->offsetSet($id, new Transactions\Transaction());
        }

        return $this->transactionCollection->offsetGet($id);
    }

    public function lastGeneratedValue(Storage|null $storage = null): int|null
    {
        // $storage ??= $this->defaultStorage;
        // TODO: Implement lastGeneratedValue() method.
        return null;
    }

    public function serializer(Storage|null $storage = null): SerializerInterface
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

    public function hasStore(Store $store, Storage|null $storage = null): bool
    {
        $storage ??= $this->defaultStorage;
        $path = $this->pathBuilder->build($storage, $store->name());

        return file_exists($path);
    }
}
