<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions;

use Medas\JsonStorage\JsonDirectory;
use Medas\JsonStorage\JsonFile;
use Medas\JsonStorage\Records\JsonRecordSet;
use Medas\StorageManager\Interfaces\RecordSet;
use Medas\StorageManager\Interfaces\Storage;
use Medas\StorageManager\UnitOfWork\Action;
use Medas\StorageManager\UnitOfWork\Priority;

abstract class JsonAction implements Action
{
    protected JsonRecordSet $recordSet;
    private \Closure|null $onComplete;

    public function __construct(
        protected JsonDirectory   $directory,
        protected JsonFile        $file,
        private readonly Priority $priority,
    )
    {
    }

    public function recordSet(): RecordSet
    {
        return $this->recordSet;
    }

    public function storage(): Storage
    {
        return $this->directory;
    }

    public function onComplete(): \Closure|null
    {
        return $this->onComplete;
    }

    public function setOnComplete(\Closure|null $onComplete): Action
    {
        $this->onComplete = $onComplete;

        return $this;
    }

    public function priority(): Priority
    {
        return $this->priority;
    }
}
