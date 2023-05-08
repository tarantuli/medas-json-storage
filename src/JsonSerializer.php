<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\Core\Attributes\Service;
use Medas\Core\Interfaces\{Serializer, Type};

#[Service]
class JsonSerializer implements Serializer
{
    public function serialize(mixed $value): mixed
    {
        return $value;
    }

    public function unserialize(mixed $value, Type $type = null): mixed
    {
        return $value;
    }
}
