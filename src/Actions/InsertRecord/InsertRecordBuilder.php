<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\InsertRecord;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\StorageFile;
use Medas\StorageManager\Interfaces\{Builders\InsertBuilder, Store};
use Medas\StorageManager\UnitOfWork\{ActionSet, Priority};

#[Service]
class InsertRecordBuilder implements InsertBuilder
{
    public function build(Store $store, array $values, Priority $priority = Priority::CreateRecord): ActionSet
    {
        /** @var StorageFile $store */
        return ActionSet::fromAction(new InsertRecord($values, $store, $priority));
    }
}
