<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\UpdateCollection;

use Medas\Core\{Interfaces\ManagedCollection, Types\Collection};
use Medas\JsonStorage\StorageFile;
use Medas\StorageManager\{Interfaces\RecordSet, UnitOfWork\BaseAction, UnitOfWork\Priority};

class UpdateCollection extends BaseAction
{
    public RecordSet $recordSet;

    public function __construct(
        public StorageFile       $file,
        public object            $entity,
        public string            $name,
        public Collection        $type,
        public ManagedCollection $values,
        Priority                 $priority = Priority::UpdateRecord,
    )
    {
        parent::__construct($this->file->storage(), $priority);
    }

    public function __serialize(): array
    {
        return [
            'file' => $this->file,
            'entity' => $this->entity,
            'name' => $this->name,
            'type' => $this->type,
            'values' => $this->values,
            'priority' => $this->priority,
        ];
    }

    public function recordSet(): RecordSet
    {
        return $this->recordSet;
    }
}
