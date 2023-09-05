<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions;

use Medas\Core\Attributes\Service;
use Medas\StorageManager\Interfaces\ActionExecutor;
use Medas\StorageManager\UnitOfWork\{Action, ActionSet};

#[Service]
readonly class Executor implements ActionExecutor
{
    public function __construct(
        private Executors\InsertExecutor     $insertExecutor,
        private Executors\CreateFileExecutor $createFileExecutor,
    )
    {
    }

    public function execute(Action $action): void
    {
        match (true) {
            $action instanceof CreateFileAction => $this->createFileExecutor->execute($action),
            $action instanceof RecordAction && $action->type === Type::InsertRecord => $this->insertExecutor->execute($action),
            default => throw new \Exception('To be implemented'),
        };
    }

    public function executeSet(ActionSet $actionSet): void
    {
        /** @var \Medas\JsonStorage\Actions\ActionSet $actionSet */
        foreach ($actionSet as $action) {
            $this->execute($action);
        }
    }
}
