<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Builders;

use Medas\Core\Attributes\Service;
use Medas\EntityManager\MetaDataManager;
use Medas\EntityManager\Selector\{
    Conditions\WhereIs,
    Operants\Argument,
    Operants\Property,
    Operants\Value,
    Selector
};
use Medas\JsonStorage\Actions\GetRecord\GetRecord;
use Medas\JsonStorage\Exceptions\SelectorNotYetImplemented;
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
        $filters = [];

        foreach ($definition->conditions as $condition) {
            if ($condition instanceof WhereIs
                    && $condition->property instanceof Property
                    && $condition->value instanceof Argument) {
                $filters[$condition->property->name] = $arguments[$condition->value->name];
            }
            elseif (
                $condition instanceof WhereIs
                && $condition->property instanceof Property
                && $condition->value instanceof Value
            ) {
                $filters[$condition->property->name] = $condition->value->value;
            }
            else {
                throw new SelectorNotYetImplemented($condition);
            }
        }

        if ($definition->sorts) {
            throw new SelectorNotYetImplemented($definition->sorts);
        }

        if ($definition->relations) {
            throw new SelectorNotYetImplemented($definition->relations);
        }

        if ($definition->pagination) {
            throw new SelectorNotYetImplemented($definition->pagination);
        }

        return ActionSet::fromAction(new GetRecord([$store], $filters));
    }
}
