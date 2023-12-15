<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\DeleteRecord;

use Medas\Core\Attributes\Service;
use Medas\StorageManager\Interfaces\{Builders\DeleteBuilder, Store};
use Medas\StorageManager\UnitOfWork\{ActionSet, Priority};

#[Service]
readonly class DeleteRecordBuilder implements DeleteBuilder
{
    public function build(Store $store, array $conditions, Priority $priority = Priority::DeleteRecord): ActionSet
    {
        return ActionSet::fromAction(new DeleteRecord($store, $conditions, $priority));
    }
}
