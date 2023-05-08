<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\StorageManager\Interfaces\Transaction;

class JsonTransaction implements Transaction
{
    public function begin(): void
    {
        // Do nothing
    }

    public function rollback(): void
    {
        // Do nothing
    }

    public function commit(): void
    {
        // Do nothing
    }
}
