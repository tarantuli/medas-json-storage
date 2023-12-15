<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\CreateStore;

use Medas\JsonStorage\StorageDirectory;
use Medas\StorageManager\{Structure\Blueprint, UnitOfWork\ActionSet};

class Job
{
    public ActionSet $actionSet;

    public function __construct(
        public readonly StorageDirectory $directory,
        public readonly Blueprint        $blueprint,
    )
    {
        $this->actionSet = new ActionSet();
    }
}
