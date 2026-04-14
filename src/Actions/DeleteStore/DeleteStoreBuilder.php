<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\DeleteStore;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\{IO\PathBuilder, StorageFile};
use Medas\StorageManager\Interfaces\{
    Builders\DeleteStoreBuilder as DeleteStoreBuilderInterface,
    Store
};
use Medas\StorageManager\UnitOfWork\ActionSet;

#[Service]
readonly class DeleteStoreBuilder implements DeleteStoreBuilderInterface
{
    public function __construct(
        private PathBuilder $pathBuilder,
    )
    {
    }

    public function build(Store $store): ActionSet
    {
        /** @var StorageFile $store */
        $path = $this->pathBuilder->build($store->storage(), $store->name());

        return ActionSet::fromAction(new DeleteStore($store, $path));
    }
}
