<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Exceptions;

use Medas\Core\Exceptions\BaseException;
use Medas\JsonStorage\JsonFile;

class StringIdNotGiven extends BaseException
{
    public function __construct(JsonFile $file)
    {
        parent::__construct($file->name());
    }

    public function pattern(): string
    {
        return 'store %s requires a string id value, but null given';
    }
}
