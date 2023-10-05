<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Builders;

use Medas\Core\Attributes\Service;
use Medas\EntityManager\MetaDataManager;
use Medas\EntityManager\Selector\Selector;
use Medas\JsonStorage\Actions\GetRecord;
use Medas\StorageManager\Interfaces\Builders\SelectorActionBuilder as SelectorActionBuilderInterface;
use Medas\StorageManager\StorageManager;
use Medas\StorageManager\UnitOfWork\ActionSet;

#[Service]
readonly class SelectorActionBuilder implements SelectorActionBuilderInterface
{
    public function __construct(
        private MetaDataManager $metaDataManager,
        private StorageManager  $storageManager,
    )
    {
    }

    public function build(Selector $selector, array $arguments): ActionSet
    {
        $metaData = $this->metaDataManager->get($selector->entity());
        $storage = $this->storageManager->byName($metaData->entity->storage);
        $store = $this->storageManager->controller($storage)->store($metaData->entity->store);

        // TODO actually process the definition
        $definition = $selector->definition();

        if ($definition->parameters || $definition->conditions || $definition->pagination || $definition->relations || $definition->sorts) {
            throw new \Exception('not yet implemented');
        }

        return ActionSet::fromAction(new GetRecord([$store], []));
    }
}
