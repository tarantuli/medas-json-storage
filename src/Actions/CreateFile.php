<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions;

use Medas\StorageManager\{StorageManager, UnitOfWork\BaseAction, UnitOfWork\Priority};

class CreateFile extends BaseAction
{
    public function __construct(
        public readonly string $storageName,
        public readonly string $path,
        public readonly string $content,
    )
    {
        parent::__construct(
            service(StorageManager::class)->byName($this->storageName),
            Priority::CreateStore
        );
    }
}
