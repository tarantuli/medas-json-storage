<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Definitions;

use Medas\StorageManager\Structure\Blueprint\Type;

readonly class Field
{
    public function __construct(
        public string $name,
        public Type   $type,
        public bool   $hasDefault = false,
        public mixed  $default = null,
    )
    {
    }
}
