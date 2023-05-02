<?php

declare(strict_types=1);

use Medas\JsonStorage\JsonStoragePackage;
use Medas\ServiceManager\{ServiceConfig, ServiceManager};

chdir(__DIR__);

new ServiceManager(function (): ServiceConfig {
    $config = new ServiceConfig();

    $config->addPackages([
        JsonStoragePackage::instance(),
    ]);

    return $config;
});
