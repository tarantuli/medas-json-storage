<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\UpdateRecord;

use Medas\Core\Attributes\Service;
use Medas\StorageManager\Interfaces\{Builders\UpdateBuilder, Store};
use Medas\StorageManager\UnitOfWork\ActionSet;

#[Service]
readonly class UpdateRecordBuilder implements UpdateBuilder
{
    public function build(Store $store, array $updates, array $conditions): ActionSet
    {
        return ActionSet::fromAction(new UpdateRecord($store, $updates, $conditions));
    }
}
