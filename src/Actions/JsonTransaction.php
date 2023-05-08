<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions;

use Medas\StorageManager\Interfaces\Transaction;

class JsonTransaction implements Transaction
{
    public function begin(): void
    {
        // TODO: Implement begin() method.
    }

    public function rollback(): void
    {
        // TODO: Implement rollback() method.
    }

    public function commit(): void
    {
        // TODO: Implement commit() method.
    }
}
