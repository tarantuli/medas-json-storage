<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Builders;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\Actions\DeleteRecord;
use Medas\StorageManager\Interfaces\{Builders\DeleteBuilder as DeleteBuilderInterface, Store};
use Medas\StorageManager\UnitOfWork\{ActionSet, Priority};

#[Service]
readonly class DeleteBuilder implements DeleteBuilderInterface
{
    public function build(Store $store, array $conditions, Priority $priority = Priority::DeleteRecord): ActionSet
    {
        return ActionSet::fromAction(new DeleteRecord($store, $conditions, $priority));
    }
}
