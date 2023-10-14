<?php

declare(strict_types=1);

use Medas\ConfigManager\ConfigManager;
use Medas\ConfigManager\ConfigManagerPackage;
use Medas\Events\EventsPackage;
use Medas\JsonStorage\JsonStoragePackage;
use Medas\JsonStorage\StorageDirectory;
use Medas\ObjectToArraySerializer\ObjectToArraySerializerPackage;
use Medas\RamseyUuidBridge\RamseyUuidBridgePackage;
use Medas\ServiceManager\{ServiceConfig, ServiceManager};
use Medas\StorageManager\StorageManager;
use Medas\StorageManagerTests\StorageManagerTestsPackage;

chdir(__DIR__);

require 'vendor/autoload.php';

new ServiceManager(function (): ServiceConfig {
    $config = new ServiceConfig();

    $config->addPackages([
        ConfigManagerPackage::instance(),
        EventsPackage::instance(),
        JsonStoragePackage::instance(),
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
