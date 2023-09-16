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
        private Executors\CreateFileExecutor   $createFileExecutor,
        private Executors\GetRecordExecutor    $getRecordExecutor,
        private Executors\InsertRecordExecutor $insertRecordExecutor,
    )
    {
    }

    public function execute(Action $action, ActionSet $actionSet = null): void
    {
        match (true) {
            $action instanceof CreateFile => $this->createFileExecutor->execute($action),
            $action instanceof InsertRecord => $this->insertRecordExecutor->execute($action),
            $action instanceof GetRecord => $this->getRecordExecutor->execute($action),
            default => throw new \Exception('To be implemented: ' . $action::class),
        };

        if ($action instanceof InsertRecord && $action->insertId && $actionSet) {
            $actionSet->lastInsertId = $action->insertId;
        }

        if ($onComplete = $action->onComplete()) {
            $insertId = $actionSet ? $actionSet->lastInsertId : ($action instanceof InsertRecord ? $action->insertId : null);
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
