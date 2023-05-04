<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Exceptions;

use Medas\Core\Exceptions\BaseException;

class IdGivenInParameterAndData extends BaseException
{
    public function __construct(mixed $inValues, mixed $inCall)
    {
        parent::__construct($inValues, $inCall);
    }

    public function pattern(): string
    {
        return 'a value for the record id was given both in the values (%s) and the function call (%s)';
    }
}
