<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\Core\Collections\GenericCollection;
use Medas\StorageManager\{
    Entities\Record,
    Interfaces\Record as RecordInterface,
    Interfaces\RecordSet as RecordSetInterface,
    Interfaces\RecordSetMetaData
};

/** @extends GenericCollection<Record> */
class RecordSet extends GenericCollection implements RecordSetInterface
{
    public function fetchRecords(): array
    {
        return $this->data;
    }

    public function fetchRecord(): RecordInterface|null
    {
        return $this->data[0] ?? null;
    }

    public function hasRecords(): bool
    {
        return $this->count() >= 1;
    }

    public function fetchMetaData(): RecordSetMetaData
    {
        // TODO: Implement fetchMetaData() method.
    }
}
