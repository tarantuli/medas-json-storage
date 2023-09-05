<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Exceptions;

use Medas\Core\Exceptions\BaseException;

class FailedToDecodeFromJson extends BaseException
{
    public function __construct(string $jsonString, string $errorMessage)
    {
        parent::__construct($jsonString, $errorMessage);
    }

    public function pattern(): string
    {
        return 'failed to decode %s: %s';
    }
}
