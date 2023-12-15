<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\DeleteRecord;

use Medas\JsonStorage\StorageFile;
use Medas\StorageManager\{Interfaces\RecordSet, UnitOfWork\BaseAction, UnitOfWork\Priority};

class DeleteRecord extends BaseAction
{
    public RecordSet $recordSet;

    public function __construct(
        public StorageFile $file,
        public array       $conditions,
        Priority           $priority = Priority::DeleteRecord,
    )
    {
        parent::__construct($this->file->storage(), $priority);
    }

    public function __serialize(): array
    {
        return [
            'file' => $this->file,
            'conditions' => $this->conditions,
            'priority' => $this->priority,
        ];
    }

    public function recordSet(): RecordSet
    {
        return $this->recordSet;
    }
}
