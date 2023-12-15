<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\CreateStore;

use Medas\Core\Attributes\Service;

#[Service]
readonly class CreateStoreExecutor
{
    public function execute(CreateStore $action): void
    {
        file_put_contents($action->path, $action->content);
    }
}
