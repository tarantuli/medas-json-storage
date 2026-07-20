<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\DeleteRecord;

use Medas\Core\Attributes\Service;
use Medas\EntityManager\Selector\Slice;
use Medas\JsonStorage\StorageFile;
use Medas\StorageManager\Interfaces\{Builders\DeleteBuilder, Store};
use Medas\StorageManager\UnitOfWork\{ActionSet, Priority};

#[Service]
readonly class DeleteRecordBuilder implements DeleteBuilder
{
    public function build(
        Store      $store,
        array      $conditions,
        Priority   $priority = Priority::DeleteRecord,
        array      $sorts = [],
        Slice|null $slice = null
    ): ActionSet
    {
        // TODO: implement $sorts and $slice
        /** @var StorageFile $store */
        return ActionSet::fromAction(new DeleteRecord($store, $conditions, $priority));
    }
}
