<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions;

use Medas\JsonStorage\StorageFile;
use Medas\StorageManager\{Interfaces\RecordSet, UnitOfWork\BaseAction, UnitOfWork\Priority};

class UpdateRecord extends BaseAction
{
    public RecordSet $recordSet;

    public function __construct(
        public StorageFile $file,
        public array       $updates,
        public array       $conditions,
        Priority           $priority = Priority::UpdateRecord,
    )
    {
        parent::__construct($this->file->storage(), $priority);
    }

    public function __serialize(): array
    {
        return [
            'file' => $this->file,
            'updates' => $this->updates,
            'conditions' => $this->conditions,
            'priority' => $this->priority,
        ];
    }

    public function recordSet(): RecordSet
    {
        return $this->recordSet;
    }
}
