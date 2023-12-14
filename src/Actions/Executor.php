<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions;

use Medas\Core\Attributes\Service;
use Medas\StorageManager\{Interfaces\ActionExecutor, UnitOfWork\Action, UnitOfWork\ActionSet};

#[Service]
readonly class Executor implements ActionExecutor
{
    public function __construct(
        private Executors\CreateFileExecutor   $createFileExecutor,
        private Executors\DeleteRecordExecutor $deleteRecordExecutor,
        private Executors\GetRecordExecutor    $getRecordExecutor,
        private Executors\InsertRecordExecutor $insertRecordExecutor,
        private Executors\UpdateRecordExecutor $updateRecordExecutor,
    )
    {
    }

    public function execute(Action $action, ActionSet $actionSet = null): void
    {
        match ($action::class) {
            CreateFile::class => $this->createFileExecutor->execute($action),
            DeleteRecord::class => $this->deleteRecordExecutor->execute($action),
            GetRecord::class => $this->getRecordExecutor->execute($action),
            InsertRecord::class => $this->insertRecordExecutor->execute(
                $action,
                $actionSet?->lastInsertId
            ),

            UpdateRecord::class => $this->updateRecordExecutor->execute($action),
            default => throw new \Exception('To be implemented: ' . $action::class),
        };

        if ($actionSet) {
            $this->updateActionSet($action, $actionSet);
        }

        if ($onComplete = $action->onComplete()) {
            $insertId = $actionSet
                ? $actionSet->lastInsertId
                : ($action instanceof InsertRecord ? $action->insertId : null);

            $onComplete($action->storage(), $insertId);
        }
    }

    public function executeSet(ActionSet $actionSet): void
    {
        foreach ($actionSet as $action) {
            $this->execute($action, $actionSet);
        }
    }

    private function updateActionSet(Action $action, ActionSet $actionSet): void
    {
        if ($action instanceof InsertRecord && $action->insertId) {
            $actionSet->lastInsertId = $action->insertId;
        }

        if ($action instanceof GetRecord) {
            $actionSet->lastRecordSet = $action->recordSet;
        }
    }
}
