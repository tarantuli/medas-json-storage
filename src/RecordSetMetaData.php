<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\StorageManager\Interfaces\{
    FieldMetaData,
    RecordSetMetaData as RecordSetMetaDataInterface
};

readonly class RecordSetMetaData implements RecordSetMetaDataInterface
{
    /**
     * @param string[]    $fieldNames
     */
    public function __construct(
        private array       $fieldNames,
        private string|null $keyName,
        private int         $rowCount,
    )
    {
    }

    /**
     * JSON storage has no schema, so field type metadata is not available.
     *
     * @return FieldMetaData[]
     */
    public function fields(): array
    {
        return [];
    }

    /** @return string[] */
    public function fieldNames(): array
    {
        return $this->fieldNames;
    }

    /** @return string[] */
    public function primaryKeyFieldNames(): array
    {
        return $this->keyName !== null ? [$this->keyName] : [];
    }

    public function rowCount(): int
    {
        return $this->rowCount;
    }
}
