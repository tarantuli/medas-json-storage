<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Builders;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\Actions\{RecordAction, Type};
use Medas\StorageManager\Interfaces\{Builders\InsertBuilder as InsertBuilderInterface, Store};
use Medas\StorageManager\UnitOfWork\{ActionSet, Priority};

#[Service]
class InsertBuilder implements InsertBuilderInterface
{
    public function build(Store $store, array $values, Priority $priority = Priority::CreateRecord): ActionSet
    {
        return new ActionSet(
            [new RecordAction(Type::InsertRecord, $values, $store, $priority)]
        );
    }
}
