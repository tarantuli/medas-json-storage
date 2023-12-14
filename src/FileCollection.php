<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\StorageManager\{Interfaces\Store, Shared\StoreCollection};

/**
 * @extends StoreCollection<StorageFile>
 */
class FileCollection extends StoreCollection
{
    protected function createStore($storage, $storeName): Store
    {
        return new StorageFile($storeName, $storage);
    }
}
