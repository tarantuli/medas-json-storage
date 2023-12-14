<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Builders;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\Actions\UpdateRecord;
use Medas\StorageManager\Interfaces\{Builders\UpdateBuilder as UpdateBuilderInterface, Store};
use Medas\StorageManager\UnitOfWork\ActionSet;

#[Service]
readonly class UpdateBuilder implements UpdateBuilderInterface
{
    public function build(Store $store, array $updates, array $conditions): ActionSet
    {
        return ActionSet::fromAction(new UpdateRecord($store, $updates, $conditions));
    }
}
