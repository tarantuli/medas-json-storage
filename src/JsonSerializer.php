<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\Core\Attributes\Service;
use Medas\Core\Interfaces\{Guid, GuidProvider, Serializer, Type};
use Medas\EntityManager\Types\Guid as GuidType;
use Medas\ServiceManager\Exceptions\GuidProviderIsNotAvailable;

#[Service]
class JsonSerializer implements Serializer
{
    public function __construct(
        private readonly GuidProvider|null $guidProvider,
    )
    {
    }

    public function serialize(mixed $value): mixed
    {
        if ($value instanceof Guid) {
            $value = $value->toBytes();
        }

        if (is_string($value)) {
            if (!mb_check_encoding($value, 'UTF-8') || str_starts_with($value, 'base64:')) {
                $value = 'base64:' . base64_encode($value);
            }
        }

        return $value;
    }

    public function unserialize(mixed $value, Type $type = null): mixed
    {
        if (is_string($value) && str_starts_with($value, 'base64:')) {
            $value = base64_decode(substr($value, 7), true);
        }

        if ($type instanceof GuidType) {
            if ($this->guidProvider === null) {
                throw new GuidProviderIsNotAvailable();
            }

            $value = $this->guidProvider->fromBytes($value);
        }

        return $value;
    }
}
