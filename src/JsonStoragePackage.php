<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\Core\{AsSingleton, BasePackage, Interfaces\ServiceConfigBuilder};
use Medas\Json\JsonPackage;
use Medas\StorageManager\{StorageManager, StorageManagerPackage};

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

    public function initialize(ServiceConfigBuilder $config): void
    {
        parent::initialize($config);

        service(StorageManager::class)->registerController(service(StorageController::class));
    }
}
