<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\DeleteStore;

use Medas\Core\Attributes\Service;

#[Service]
readonly class DeleteStoreExecutor
{
    public function execute(DeleteStore $action): void
    {
        if (file_exists($action->path)) {
            unlink($action->path);
        }
    }
}
