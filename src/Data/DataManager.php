<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Data;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\IO\{FileReader, FileWriter};
use Medas\JsonStorage\StorageFile;

#[Service]
class DataManager
{
    /** @var FileData[] */
    private array $dataObjects = [];

    public function __construct(
        private readonly FileReader $reader,
        private readonly FileWriter $writer,
    )
    {
    }

    public function get(StorageFile $file): FileData
    {
        $id = spl_object_id($file);

        if (!isset($this->dataObjects[$id])) {
            $content = $this->reader->read($file);

            $this->dataObjects[$id] = new FileData($file, $content);
        }

        return $this->dataObjects[$id];
    }

    public function flush(): void
    {
        foreach ($this->dataObjects as $fileData) {
            $this->writer->write($fileData);
            $fileData->hasBeenFlushed();
        }
    }

    public function rollback(): void
    {
        foreach ($this->dataObjects as $id => $fileData) {
            if ($fileData->hasUnflushedChanges()) {
                unset($this->dataObjects[$id]);
            }
        }
    }
}
