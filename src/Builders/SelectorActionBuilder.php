<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Builders;

use Medas\Core\Attributes\Service;
use Medas\EntityManager\Exceptions\{GroupingsThroughRepository, OutputValuesThroughRepository};
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

    public function build(Selector $selector, array $arguments, bool $doCount = false): ActionSet
    {
        // TODO: implement $doCount
        $metaData = $this->metaDataManager->get($selector->entity());
        $storage = $this->storageManager->byName($metaData->entity->storage);
        $store = $this->storageManager->controller($storage)->store($metaData->entity->store);
        $definition = $selector->definition();
        $filters = [];

        // The Repository turns every record into an entity, so the storage refuses what does not
        // select the entity's own records (see Repository::fetch())
        if ($definition->outputValues) {
            throw new OutputValuesThroughRepository($selector->entity());
        }

        if ($definition->groupings) {
            throw new GroupingsThroughRepository($selector->entity());
        }

        // Not ignored: StoreRecordManager would look for them in each record
        if ($definition->derivedValues) {
            throw new SelectorNotYetImplemented($definition->derivedValues);
        }

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

        return ActionSet::fromAction(new GetRecord([$store], $filters, slice: $definition->slice));
    }
}
