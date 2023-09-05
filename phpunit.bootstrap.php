<?php

declare(strict_types=1);

use Medas\ConfigManager\ConfigManager;
use Medas\JsonStorage\JsonStoragePackage;
use Medas\JsonStorage\StorageDirectory;
use Medas\ServiceManager\{ServiceConfig, ServiceManager};
use Medas\StorageManager\StorageManager;
use Medas\StorageManagerTests\StorageManagerTestsPackage;

chdir(__DIR__);

require 'vendor/autoload.php';

new ServiceManager(function (): ServiceConfig {
    $config = new ServiceConfig();

    $config->addPackages([
        JsonStoragePackage::instance(),
        StorageManagerTestsPackage::instance(),
    ]);

    return $config;
});

service(ConfigManager::class)
    ->addDirectory(__DIR__ . '/vendor/morphp/medas-storage-manager-tests/src')
    ->readEnv(__DIR__);

service(StorageManager::class)
    ->add(new StorageDirectory('tests/TestStorage'));
