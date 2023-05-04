<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Definitions;

class Definition
{
    public function __construct(
        public ScalarType $idType = ScalarType::Integer,
        public string     $idName = 'id',
        /** @var Field[] */
        public array      $fields = [],
    )
    {
    }
}
