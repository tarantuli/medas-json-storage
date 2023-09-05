<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Exceptions;

use Medas\Core\Exceptions\BaseException;

class FailedToEncodeToJson extends BaseException
{
    public function __construct(mixed $jsonString, string $errorMessage)
    {
        parent::__construct($jsonString, $errorMessage);
    }

    public function pattern(): string
    {
        return 'failed to encode %s: %s';
    }
}
