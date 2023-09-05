<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\StorageManager\Interfaces\Store;
use Medas\StorageManager\Shared\StoreCollection;

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
