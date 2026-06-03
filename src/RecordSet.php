<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\Core\Collections\GenericCollection;
use Medas\StorageManager\{
    Entities\Record,
    Interfaces\Record as RecordInterface,
    Interfaces\RecordSet as RecordSetInterface,
    Interfaces\RecordSetMetaData as RecordSetMetaDataInterface
};

/** @extends GenericCollection<Record> */
class RecordSet extends GenericCollection implements RecordSetInterface
{
    public function __construct(
        array                        $data = [],
        private readonly array       $fieldNames = [],
        private readonly string|null $keyName = null,
    )
    {
        parent::__construct($data);
    }

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

    public function fetchMetaData(): RecordSetMetaDataInterface
    {
        return new RecordSetMetaData(
            fieldNames: $this->fieldNames,
            keyName: $this->keyName,
            rowCount: $this->count(),
        );
    }
}
