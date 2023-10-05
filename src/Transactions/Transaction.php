<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Transactions;

use Medas\JsonStorage\Data\DataManager;
use Medas\StorageManager\Interfaces\Transaction as TransactionInterface;

class Transaction implements TransactionInterface
{
    public function begin(): void
    {
        service(DataManager::class)->flush();
    }

    public function rollback(): void
    {
        service(DataManager::class)->rollback();
    }

    public function commit(): void
    {
        service(DataManager::class)->flush();
    }
}
