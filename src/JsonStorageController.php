<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\Core\Interfaces\Serializer;
use Medas\StorageManager\Interfaces\{ActionBuilder, StorageController, Transaction};
use Medas\StorageManager\Migrations\MigrationBuilder;

class JsonStorageController implements StorageController
{
    public function __construct()
    {
    }

    public function transaction(): Transaction
    {
        // TODO: Implement transaction() method.
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
        // TODO: Implement serializer() method.
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
