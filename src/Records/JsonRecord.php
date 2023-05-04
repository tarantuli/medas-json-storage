<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Records;

use Medas\StorageManager\Bases\Record;

class JsonRecord extends Record
{
    public function __construct(
        array                      $data,
        public readonly int|string $id,
    )
    {
        parent::__construct($data);
    }
}
