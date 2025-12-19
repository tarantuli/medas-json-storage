<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions;

use Medas\Core\Attributes\Service;
use Medas\StorageManager\{Interfaces\ActionExecutor, UnitOfWork\Action, UnitOfWork\ActionSet};

#[Service]
readonly class Executor implements ActionExecutor
{
    public function __construct(
        private CreateStore\CreateStoreExecutor           $createStoreExecutor,
        private DeleteRecord\DeleteRecordExecutor         $deleteRecordExecutor,
        private GetRecord\GetRecordExecutor               $getRecordExecutor,
        private InsertRecord\InsertRecordExecutor         $insertRecordExecutor,
        private UpdateCollection\UpdateCollectionExecutor $updateCollectionExecutor,
        private UpdateRecord\UpdateRecordExecutor         $updateRecordExecutor,
    )
    {
    }

    public function execute(Action $action, ActionSet|null $actionSet = null): void
    {
        match ($action::class) {
            CreateStore\CreateStore::class => $this->createStoreExecutor->execute($action),
            DeleteRecord\DeleteRecord::class => $this->deleteRecordExecutor->execute($action),
            GetRecord\GetRecord::class => $this->getRecordExecutor->execute($action),
            InsertRecord\InsertRecord::class => $this->insertRecordExecutor->execute(
                $action,
                $actionSet?->lastInsertId
            ),

            UpdateCollection\UpdateCollection::class => $this->updateCollectionExecutor->execute($action),
            UpdateRecord\UpdateRecord::class => $this->updateRecordExecutor->execute($action),
            default => throw new \Exception('To be implemented: ' . $action::class),
        };

        if ($actionSet) {
            $this->updateActionSet($action, $actionSet);
        }

        if ($onComplete = $action->onComplete()) {
            $insertId = $actionSet
                ? $actionSet->lastInsertId
                : ($action instanceof InsertRecord\InsertRecord ? $action->insertId : null);

            $onComplete($action->storage(), $insertId);
        }
    }

    private function updateActionSet(Action $action, ActionSet $actionSet): void
    {
        if ($action instanceof InsertRecord\InsertRecord && $action->insertId) {
            $actionSet->lastInsertId = $action->insertId;
        }

        if ($action instanceof GetRecord\GetRecord) {
            $actionSet->lastRecordSet = $action->recordSet;
        }
    }

    public function executeSet(ActionSet $actionSet): void
    {
        foreach ($actionSet as $action) {
            $this->execute($action, $actionSet);
        }
    }
}
