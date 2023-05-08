<?php

declare(strict_types=1);

namespace Medas\JsonStorage\ConfigOptions;

use Medas\Core\AsSingleton;
use Medas\Core\Interfaces\ConfigGroup;
use Medas\StorageManager\ConfigOptions\RootGroup;

class JsonGroup implements ConfigGroup
{
    use AsSingleton;

    public function parent(): ConfigGroup|null
    {
        return RootGroup::instance();
    }

    public function name(): string
    {
        return 'json';
    }
}
