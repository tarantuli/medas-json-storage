<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Builders;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\Actions\GetRecord;
use Medas\StorageManager\Interfaces\Builders\GetBuilder as GetBuilderInterface;
use Medas\StorageManager\UnitOfWork\ActionSet;

#[Service]
readonly class GetBuilder implements GetBuilderInterface
{
    public function build(array $stores, array $filters): ActionSet
    {
        return ActionSet::fromAction(new GetRecord($stores, $filters));
    }
}
