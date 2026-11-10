<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\GetRecord;

use Medas\EntityManager\Selector\Slice;
use Medas\JsonStorage\StorageFile;
use Medas\StorageManager\{Interfaces\RecordSet, UnitOfWork\BaseAction, UnitOfWork\Priority};

class GetRecord extends BaseAction
{
    public RecordSet $recordSet;

    /** @param StorageFile[] $files */
    public function __construct(
        public readonly array      $files,
        public array               $criteria,
        Priority                   $priority = Priority::Default,
        public readonly Slice|null $slice = null,
    )
    {
        parent::__construct($this->files[0]->storage(), $priority);
    }

    public function __serialize(): array
    {
        return [
            'files' => $this->files,
            'criteria' => $this->criteria,
            'priority' => $this->priority,
            'slice' => $this->slice,
        ];
    }

    public function recordSet(): RecordSet
    {
        return $this->recordSet;
    }
}
