<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions;

use Medas\JsonStorage\StorageFile;
use Medas\StorageManager\Interfaces\RecordSet;
use Medas\StorageManager\UnitOfWork\{BaseAction, Priority};

class RecordAction extends BaseAction
{
    public mixed $insertId;

    public RecordSet $recordSet;

    public function __construct(
        public readonly Type        $type,
        public mixed                $data,
        public readonly StorageFile $file,
        Priority                    $priority = Priority::Default
    )
    {
        $this->storage = $this->file->storage();
        $this->priority = $priority;
    }

    public function recordSet(): RecordSet
    {
        return $this->recordSet;
    }
}
