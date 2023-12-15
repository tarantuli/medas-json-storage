<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\InsertRecord;

use Medas\JsonStorage\StorageFile;
use Medas\StorageManager\{Interfaces\RecordSet, UnitOfWork\BaseAction, UnitOfWork\Priority};

class InsertRecord extends BaseAction
{
    public mixed $insertId;
    public RecordSet $recordSet;

    public function __construct(
        public mixed                $data,
        public readonly StorageFile $file,
        Priority                    $priority = Priority::Default
    )
    {
        parent::__construct($this->file->storage(), $priority);
    }

    public function __serialize(): array
    {
        return [
            'file' => $this->file,
            'data' => $this->data,
            'priority' => $this->priority,
        ];
    }

    public function recordSet(): RecordSet
    {
        return $this->recordSet;
    }
}
