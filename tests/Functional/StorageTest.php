<?php

declare(strict_types=1);

namespace Medas\JsonStorageTest\Functional;

use Medas\JsonStorage\JsonDirectory;
use Medas\StorageManager\StorageManager;
use Medas\StorageManagerTest\Functional\StorageTests\AbstractStorageTestClass;

class StorageTest extends AbstractStorageTestClass
{
    private const STORAGE = __DIR__ . DIRECTORY_SEPARATOR . 'storage_test';

    protected function initialize(): void
    {
        service(StorageManager::class)->add(
            new JsonDirectory(self::STORAGE)
        );
    }
}
