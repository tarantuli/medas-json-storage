<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions;

use Medas\Core\Collections\GenericCollection;
use Medas\StorageManager\Interfaces\RecordSet;
use Medas\StorageManager\UnitOfWork\Action;
use Medas\StorageManager\UnitOfWork\ActionSet as ActionSetInterface;

/**
 * @extends GenericCollection<Action>
 */
class ActionSet extends GenericCollection implements ActionSetInterface
{
    private RecordSet|null $lastRecordSet = null;

    public static function fromAction(Action $query): self
    {
        return new self([$query]);
    }

    public function recordSet(): RecordSet
    {
        return $this->lastRecordSet;
    }

    public function setRecordSet(RecordSet $recordSet): void
    {
        $this->lastRecordSet = $recordSet;
    }
}
