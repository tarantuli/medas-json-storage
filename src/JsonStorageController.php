<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\Core\Interfaces\Serializer;
use Medas\StorageManager\Interfaces\{ActionBuilder, StorageController, Transaction};
use Medas\StorageManager\Migrations\MigrationBuilder;

class JsonStorageController implements StorageController
{
    private JsonSerializer $serializer;
    private JsonTransaction $transaction;

    public function __construct()
    {
        $this->serializer = service(JsonSerializer::class);
        $this->transaction = new JsonTransaction();
    }

    public function transaction(): Transaction
    {
        return $this->transaction;
    }

    public function deleteStore(string $name): void
    {
        // TODO: Implement deleteStore() method.
    }

    public function lastGeneratedValue(): int|null
    {
        // TODO: Implement lastGeneratedValue() method.
    }

    public function serializer(): Serializer
    {
        return $this->serializer;
    }

    public function actionBuilder(): ActionBuilder
    {
        // TODO: Implement actionBuilder() method.
    }

    public function migrationBuilder(): MigrationBuilder
    {
        // TODO: Implement actionBuilder() method.
    }
}
