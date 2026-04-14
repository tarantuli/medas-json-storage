<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\DeleteStore;

use Medas\JsonStorage\StorageFile;
use Medas\StorageManager\UnitOfWork\{BaseAction, Priority};

class DeleteStore extends BaseAction
{
    public function __construct(
        public readonly StorageFile $file,
        public readonly string      $path,
    )
    {
        parent::__construct($this->file->storage(), Priority::DeleteStore);
    }
}
