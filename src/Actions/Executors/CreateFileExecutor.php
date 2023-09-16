<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\Executors;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\Actions\CreateFile;

#[Service]
readonly class CreateFileExecutor
{
    public function execute(CreateFile $action): void
    {
        file_put_contents($action->path, $action->content);
    }
}
