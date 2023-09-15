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

    public function execute(Action $action, ActionSet $actionSet = null): void
    {
        match (true) {
            $action instanceof CreateFileAction => $this->createFileExecutor->execute($action),
            $action instanceof RecordAction && $action->type === Type::InsertRecord => $this->insertExecutor->execute($action),
            default => throw new \Exception('To be implemented'),
        };

        if ($action instanceof RecordAction && $action->insertId && $actionSet) {
            $actionSet->lastInsertId = $action->insertId;
        }

        if ($onComplete = $action->onComplete()) {
            $insertId = $actionSet ? $actionSet->lastInsertId : ($action instanceof RecordAction ? $action->insertId : null);
            $onComplete($action->storage(), $insertId);
        }
    }

    public function executeSet(ActionSet $actionSet): void
    {
        foreach ($actionSet as $action) {
            $this->execute($action, $actionSet);
        }
    }
}
