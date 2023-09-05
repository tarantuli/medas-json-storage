<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\Executors;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\Actions\CreateFileAction;

#[Service]
readonly class CreateFileExecutor
{
    public function execute(CreateFileAction $action): void
    {
        file_put_contents($action->path, $action->content);
    }
}
