<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\Core\Attributes\ConfigValue;
use Medas\Core\Interfaces\DirectoryManager;
use Medas\JsonStorage\ConfigOptions\DirectoryPath;
use Medas\StorageManager\Interfaces\{Storage, StorageController, Store};

class JsonDirectory implements Storage
{
    /** @var JsonFile[] */
    private array $files = [];

    private JsonStorageController $controller;

    public function __construct(
        #[ConfigValue(DirectoryPath::class)] public readonly string $directory,
    )
    {
        service(DirectoryManager::class)->create($this->directory);
        $this->controller = new JsonStorageController();
    }

    public function stores(): array
    {
        // TODO: fetch all from the directory
        return $this->files;
    }

    public function store(string $name): Store
    {
        if (!isset($this->files[$name])) {
            $this->files[$name] = new JsonFile($this, $name);
        }

        return $this->files[$name];
    }

    public function controller(): StorageController
    {
        return $this->controller;
    }
}
