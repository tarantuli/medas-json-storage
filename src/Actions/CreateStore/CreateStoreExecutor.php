<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\CreateStore;

use Medas\Core\{Attributes\Service, Interfaces\DirectoryCreator};

#[Service]
readonly class CreateStoreExecutor
{
    public function __construct(
        private DirectoryCreator $directoryCreator,
    )
    {
    }

    public function execute(CreateStore $action): void
    {
        $this->directoryCreator->create(dirname($action->path));

        file_put_contents($action->path, $action->content);
    }
}
