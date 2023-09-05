<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\Core\Attributes\Service;

#[Service]
class Json
{
    public function encode(mixed $value): string
    {
        $string = json_encode($value, JSON_PRETTY_PRINT);

        if (json_last_error()) {
            throw new Exceptions\FailedToEncodeToJson($value, json_last_error_msg());
        }

        return $string;
    }

    public function decode(string $string): mixed
    {
        $variable = json_decode($string, true);

        if (json_last_error()) {
            throw new Exceptions\FailedToDecodeFromJson($string, json_last_error_msg());
        }

        return $variable;
    }
}
