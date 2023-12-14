<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions;

use Medas\JsonStorage\StorageFile;
use Medas\StorageManager\{Interfaces\RecordSet, UnitOfWork\BaseAction, UnitOfWork\Priority};

class GetRecord extends BaseAction
{
    public RecordSet $recordSet;

    /** @param StorageFile[] $files */
    public function __construct(
        public readonly array $files,
        public array          $criteria,
        Priority              $priority = Priority::Default
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
        ];
    }

    public function recordSet(): RecordSet
    {
        return $this->recordSet;
    }
}
