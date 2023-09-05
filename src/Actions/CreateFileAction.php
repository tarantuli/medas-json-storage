<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions;

use Medas\StorageManager\Interfaces\RecordSet;
use Medas\StorageManager\StorageManager;
use Medas\StorageManager\UnitOfWork\BaseAction;
use Medas\StorageManager\UnitOfWork\Priority;

class CreateFileAction extends BaseAction
{
    public function __construct(
        public readonly string $storageName,
        public readonly string $path,
        public readonly string $content,
    )
    {
        $this->storage = service(StorageManager::class)->byName($this->storageName);
        $this->priority = Priority::CreateStore;
    }

    public function recordSet(): RecordSet
    {
        //
    }
}
