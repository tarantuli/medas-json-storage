<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\StorageManager\Interfaces\{Storage, Store};

readonly class StorageFile implements Store
{
    public function __construct(
        private string  $name,
        private Storage $storage,
    )
    {
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return StorageDirectory
     */
    public function storage(): Storage
    {
        return $this->storage;
    }
}
