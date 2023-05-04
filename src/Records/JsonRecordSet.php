<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Records;

use Medas\StorageManager\Interfaces\RecordSet;

class JsonRecordSet implements RecordSet
{
    private int $i = 0;

    public function __construct(
        /** @var JsonRecord[] */
        private readonly array $records,
    )
    {
    }

    public function fetchRecords(): array
    {
        return $this->records;
    }

    public function fetchRecord(): JsonRecord|null
    {
        return $this->records[$this->i++] ?? null;
    }

    public function hasRecords(): bool
    {
        return $this->records !== [];
    }
}
