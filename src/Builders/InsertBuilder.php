<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Builders;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\Actions\ActionSet;
use Medas\JsonStorage\Actions\RecordAction;
use Medas\JsonStorage\Actions\Type;
use Medas\StorageManager\Interfaces\Builders\InsertBuilder as InsertBuilderInterface;
use Medas\StorageManager\Interfaces\Store;
use Medas\StorageManager\UnitOfWork\Priority;

#[Service]
class InsertBuilder implements InsertBuilderInterface
{
    public function build(Store $store, array $values, Priority $priority = Priority::CreateRecord): ActionSet
    {
        return ActionSet::fromAction(
            new RecordAction(Type::InsertRecord, $values, $store, $priority)
        );
    }
}
