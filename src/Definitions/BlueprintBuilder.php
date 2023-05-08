<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Definitions;

use Medas\Core\Attributes\Service;
use Medas\StorageManager\Structure\Blueprint;

#[Service]
class BlueprintBuilder
{
    public function build(Definition $definition): Blueprint
    {
        diedump($definition);
    }
}
