<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Transactions;

use Medas\StorageManager\Interfaces\Transaction as TransactionInterface;

class Transaction implements TransactionInterface
{
    public function begin(): void
    {
        // Do nothing for now
    }

    public function rollback(): void
    {
        // Do nothing for now
    }

    public function commit(): void
    {
        // Do nothing for now
    }
}
