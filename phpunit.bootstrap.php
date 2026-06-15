<?php

declare(strict_types=1);

use Medas\ConfigManager\{ConfigManager, ConfigManagerPackage};
use Medas\Events\EventsPackage;
use Medas\JsonStorage\{JsonStoragePackage, StorageDirectory};
use Medas\ObjectInstantiator\{ObjectInstantiator, ObjectInstantiatorPackage};
use Medas\ObjectToArraySerializer\ObjectToArraySerializerPackage;
use Medas\RamseyUuidBridge\RamseyUuidBridgePackage;
use Medas\ServiceManager\{ServiceConfigBuilder, ServiceManager};
use Medas\StorageManager\StorageManager;
use Medas\StorageManagerTests\StorageManagerTestsPackage;

chdir(__DIR__);

require 'vendor/autoload.php';

new ServiceManager(function (): ServiceConfigBuilder {
    $config = new ServiceConfigBuilder(ObjectInstantiator::class);

    $config->addPackages([
        ConfigManagerPackage::instance(),
        EventsPackage::instance(),
        JsonStoragePackage::instance(),
        ObjectInstantiatorPackage::instance(),
        ObjectToArraySerializerPackage::instance(),
        RamseyUuidBridgePackage::instance(),
        StorageManagerTestsPackage::instance(),
    ]);

    return $config;
});

service(ConfigManager::class)
    ->addDirectory(__DIR__ . '/vendor/morphp/medas-storage-manager-tests/src')
    ->readEnv(__DIR__);

service(StorageManager::class)
    ->add(new StorageDirectory('tests/TestStorage'));
