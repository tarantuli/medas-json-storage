<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\UpdateCollection;

use Medas\Core\{Attributes\Service, Interfaces\ManagedCollection};
use Medas\EntityManager\Types\Collection;
use Medas\StorageManager\Interfaces\{Builders\CollectionUpdateBuilder, Store};
use Medas\StorageManager\UnitOfWork\ActionSet;

#[Service]
readonly class UpdateCollectionBuilder implements CollectionUpdateBuilder
{
    public function build(
        Store             $store,
        object            $entity,
        string            $name,
        Collection        $type,
        ManagedCollection $values
    ): ActionSet
    {
        return ActionSet::fromAction(new UpdateCollection($store, $entity, $name, $type, $values));
    }
}
