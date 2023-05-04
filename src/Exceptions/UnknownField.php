<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Exceptions;

use Medas\Core\Exceptions\BaseException;
use Medas\JsonStorage\JsonFile;

class UnknownField extends BaseException
{

    public function __construct(JsonFile $file, string $fieldName
    )
    {
        parent::__construct($file->name(), $fieldName);
    }

    public function pattern(): string
    {
        return 'store %s does not have a field named %s';
    }
}
