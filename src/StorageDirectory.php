<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\StorageManager\Interfaces\Storage;

readonly class StorageDirectory implements Storage
{
    public function __construct(
        public string  $directory,
        private string $name = 'default',
    )
    {
    }

    public function name(): string
    {
        return $this->name;
    }
}
