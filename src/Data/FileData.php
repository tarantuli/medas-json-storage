<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Data;

use Medas\JsonStorage\StorageFile;

class FileData
{
    private bool $hasUnflushedChanges = false;

    public function __construct(
        public readonly StorageFile $file,
        public readonly string|null $keyName,
        private array                $data,
    )
    {
    }

    public function data(): array
    {
        return $this->data;
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

        return [$this->keyName => $key] + $this->data[$key];
    }

    public function setDatum(mixed $key, array $data): void
    {
        $this->data[$key] = $data;
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
