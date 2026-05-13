<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Builders;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\Actions\{
    DeleteRecord\DeleteRecordBuilder,
    GetRecord\GetRecordBuilder,
    InsertRecord\InsertRecordBuilder,
    UpdateCollection\UpdateCollectionBuilder,
    UpdateRecord\UpdateRecordBuilder
};
use Medas\StorageManager\Interfaces\{ActionBuilders as ActionBuildersInterface, Builders};

#[Service]
readonly class ActionBuilders implements ActionBuildersInterface
{
    public function selectorAction(): Builders\SelectorActionBuilder
    {
        return service(SelectorActionBuilder::class);
    }

    public function insert(): Builders\InsertBuilder
    {
        return service(InsertRecordBuilder::class);
    }

    public function get(): Builders\GetBuilder
    {
        return service(GetRecordBuilder::class);
    }

    public function update(): Builders\UpdateBuilder
    {
        return service(UpdateRecordBuilder::class);
    }

    public function delete(): Builders\DeleteBuilder
    {
        return service(DeleteRecordBuilder::class);
    }

    public function collectionUpdate(): Builders\CollectionUpdateBuilder
    {
        return service(UpdateCollectionBuilder::class);
    }
}
