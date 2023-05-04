<?php

declare(strict_types=1);

use Medas\ConfigManager\ConfigManager;
use Medas\ConfigManager\ConfigManagerPackage;
use Medas\JsonStorage\JsonStoragePackage;
use Medas\ServiceManager\{ServiceConfig, ServiceManager};

chdir(__DIR__);

new ServiceManager(function (): ServiceConfig {
    $config = new ServiceConfig();

    $config->addPackages([
        JsonStoragePackage::instance(),
        ConfigManagerPackage::instance(),
    ]);

    return $config;
});

service(ConfigManager::class)
    ->addDirectory(__DIR__ . '/tests/MockUps')
    ->readEnv(__DIR__);
