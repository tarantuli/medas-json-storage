<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\Core\{
    Attributes\Service,
    Exceptions\GuidProviderIsNotAvailable,
    Interfaces\Guid,
    Interfaces\GuidProvider,
    Interfaces\Serializer as SerializerInterface,
    Interfaces\Type
};
use Medas\EntityManager\Types\{Boolean, Guid as GuidType, Relation};

#[Service]
readonly class Serializer implements SerializerInterface
{
    private const ENCODING_PREFIX = 'base64:';

    public function __construct(
        private GuidProvider|null $guidProvider,
    )
    {
    }

    public function serialize(mixed $value): mixed
    {
        if ($value instanceof Guid) {
            $value = $value->toBytes();
        }

        if (is_string($value)) {
            if (!mb_check_encoding($value, 'UTF-8') || str_starts_with($value, self::ENCODING_PREFIX)) {
                $value = self::ENCODING_PREFIX . base64_encode($value);
            }
        }

        return $value;
    }

    public function unserialize(mixed $value, Type $type = null): mixed
    {
        if (is_string($value) && str_starts_with($value, self::ENCODING_PREFIX)) {
            $value = base64_decode(substr($value, 7), true);
        }

        if ($type instanceof GuidType) {
            if ($this->guidProvider === null) {
                throw new GuidProviderIsNotAvailable();
            }

            $value = $this->guidProvider->fromBytes($value);
        }

        if ($type instanceof Boolean) {
            return (bool) $value;
        }

        if ($type instanceof Relation) {
            if (enum_exists($type->entity)) {
                return $value;
            }

            return em()->get($type->entity, $value);
        }

        return $value;
    }
}
