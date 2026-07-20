<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\Core\{AsSingleton, BasePackage};
use Medas\Json\JsonPackage;
use Medas\StorageManager\StorageManagerPackage;

class JsonStoragePackage extends BasePackage
{
    use AsSingleton;

    public function dependencies(): array
    {
        return [
            JsonPackage::instance(),
            StorageManagerPackage::instance(),
        ];
    }

    public function sourceDirectory(): string
    {
        return __DIR__;
    }
}
