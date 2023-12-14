<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Data;

use Medas\JsonStorage\StorageFile;

class FileData
{
    public readonly string|null $keyName;
    public readonly array $fieldNames;
    private array $data;
    private bool $hasUnflushedChanges = false;
    private array $baseRecord;

    public function __construct(
        public readonly StorageFile $file,
        array                       $content,
    )
    {
        $this->keyName = $content['keyName'];
        $this->fieldNames = $content['fieldNames'];
        $this->data = $content['data'];
        $this->baseRecord = array_fill_keys($this->fieldNames, null);
    }

    public function data(): iterable
    {
        foreach ($this->data as $key => $datum) {
            yield $key => $this->getDatum($key);
        }
    }

    public function content(): array
    {
        return [
            'keyName' => $this->keyName,
            'fieldNames' => $this->fieldNames,
            'data' => $this->data,
        ];
    }

    public function dataCount(): int
    {
        return count($this->data);
    }

    public function getDatum(mixed $key): array|null
    {
        if (!isset($this->data[$key])) {
            return null;
        }

        if ($this->keyName === null) {
            return $this->data[$key];
        }

        return array_merge($this->baseRecord, [$this->keyName => $key] + $this->data[$key]);
    }

    public function setDatum(mixed $key, array $data): void
    {
        $this->data[$key] = $data;
        $this->hasUnflushedChanges = true;
    }

    public function unsetDatum(mixed $key): void
    {
        unset($this->data[$key]);

        $this->hasUnflushedChanges = true;
    }

    public function hasUnflushedChanges(): bool
    {
        return $this->hasUnflushedChanges;
    }

    public function hasBeenFlushed(): void
    {
        $this->hasUnflushedChanges = false;
    }
}
