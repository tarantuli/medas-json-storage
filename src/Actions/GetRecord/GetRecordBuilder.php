<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\GetRecord;

use Medas\Core\Attributes\Service;
use Medas\StorageManager\Interfaces\Builders\GetBuilder;
use Medas\StorageManager\UnitOfWork\ActionSet;

#[Service]
readonly class GetRecordBuilder implements GetBuilder
{
    public function build(array $stores, array $filters): ActionSet
    {
        return ActionSet::fromAction(new GetRecord($stores, $filters));
    }
}
