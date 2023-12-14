<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Builders;

use Medas\Core\Attributes\Service;
use Medas\StorageManager\Interfaces\{ActionBuilders as ActionBuildersInterface, Builders};

#[Service]
readonly class ActionBuilders implements ActionBuildersInterface
{
    public function createStore(): Builders\CreateStoreBuilder
    {
        return service(CreateStoreBuilder::class);
    }

    public function deleteStore(): Builders\DeleteStoreBuilder
    {
        // TODO: Implement deleteStore() method.
    }

    public function selectorAction(): Builders\SelectorActionBuilder
    {
        return service(SelectorActionBuilder::class);
    }

    public function insert(): Builders\InsertBuilder
    {
        return service(InsertBuilder::class);
    }

    public function get(): Builders\GetBuilder
    {
        return service(GetBuilder::class);
    }

    public function update(): Builders\UpdateBuilder
    {
        // TODO: Implement update() method.
    }

    public function delete(): Builders\DeleteBuilder
    {
        // TODO: Implement delete() method.
    }

    public function collectionUpdate(): Builders\CollectionUpdateBuilder
    {
        // TODO: Implement collectionUpdate() method.
    }
}
