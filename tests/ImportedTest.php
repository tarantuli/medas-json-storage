<?php

declare(strict_types=1);

namespace Medas\JsonStorageTest;

use Medas\StorageManager\{
    Interfaces\Storage,
    Interfaces\StorageController,
    Interfaces\Store,
    StorageManager
};
use Medas\StorageManagerTests\Functional\{
    ConsoleCommandsTest,
    DatabaseManagerTest,
    DefaultValuesTest,
    EntityPersisterTest,
    EnumTest,
    GuidTest,
    HandledPropertyTest,
    HydratorTest,
    InheritanceTest,
    ManyToManyRelationTest
};
use PHPUnit\Framework\TestCase;

class ImportedTest extends TestCase
{
    use InheritanceTest;
    use EntityPersisterTest;
    use GuidTest;
    use DefaultValuesTest;
    use ConsoleCommandsTest;
    use DatabaseManagerTest;
    use EnumTest;
    use HandledPropertyTest;
    use HydratorTest;
    use ManyToManyRelationTest;

    private Storage $storage;
    private StorageController $controller;

    protected function storage(): Storage
    {
        if (!isset($this->storage)) {
            $this->storage = service(StorageManager::class)->byName('default');
        }

        return $this->storage;
    }

    protected function store(string $name): Store
    {
        return $this->controller()->store($name);
    }

    protected function controller(): StorageController
    {
        if (!isset($this->controller)) {
            $this->controller = service(StorageManager::class)->controller($this->storage());
        }

        return $this->controller;
    }

    protected function checkBackedEnumMigration(string $migration): void
    {
        // Do nothing
    }
}
