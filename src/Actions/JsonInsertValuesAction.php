<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions;

use Medas\JsonStorage\JsonDirectory;
use Medas\JsonStorage\JsonFile;
use Medas\StorageManager\UnitOfWork\Priority;

class JsonInsertValuesAction extends JsonAction
{
    public function __construct(
        protected JsonDirectory $directory,
        protected JsonFile      $file,
        private readonly mixed  $id,
        private readonly array  $values,
    )
    {
        parent::__construct($this->directory, $this->file, Priority::CreateRecord);
    }

    public function execute(): void
    {
        $this->recordSet = $this->file->insertValues($this->values, $this->id);
    }
}
