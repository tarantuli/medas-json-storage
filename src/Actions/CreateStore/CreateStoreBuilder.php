<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\CreateStore;

use Medas\Core\Attributes\{ConfigValue, Service};
use Medas\Json\JsonEncoder;
use Medas\JsonStorage\{IO\PathBuilder, StorageDirectory};
use Medas\StorageManager\ConfigOptions\OriginalClassStorage\DefaultStrategy;
use Medas\StorageManager\Inheritance\OriginalClassStorageStrategy;
use Medas\StorageManager\Interfaces\{
    Builders\CreateStoreBuilder as CreateStoreBuilderInterface,
    Storage
};
use Medas\StorageManager\Structure\Blueprint;
use Medas\StorageManager\UnitOfWork\ActionSet;

#[Service]
readonly class CreateStoreBuilder implements CreateStoreBuilderInterface
{
    public function __construct(
        private JsonEncoder                  $encoder,
        private PathBuilder                  $pathBuilder,

        #[ConfigValue(DefaultStrategy::class)]
        private OriginalClassStorageStrategy $originalClassStorageStrategy,
    )
    {
    }

    public function build(Storage $storage, Blueprint $blueprint): ActionSet
    {
        /** @var StorageDirectory $storage */
        $job = new Job($storage, $blueprint);

        $this->addBasicStore($job);
        $this->handleOriginalEntityType($job);

        return $job->actionSet;
    }

    private function createContent(Blueprint $blueprint): array
    {
        $fieldNames = [];
        $defaults = [];

        foreach ($blueprint->fields as $field) {
            if ($field->store === $blueprint->name) {
                $fieldNames[] = $field->name;

                $defaults[$field->name] = $field->type === Blueprint\Type::Collection
                    ? []
                    : $field->default;
            }
        }

        return [
            'keyName' => $blueprint->primaryIndex()?->fields()[0]?->name,
            'fieldNames' => $fieldNames,
            'defaults' => $defaults,
            'data' => [],
        ];
    }

    private function addBasicStore(Job $job): void
    {
        $path = $this->pathBuilder->build($job->directory, $job->blueprint->name);

        $job->actionSet[] = new CreateStore(
            $job->directory->name(),
            $path,
            $this->encoder->encode($this->createContent($job->blueprint), true)
        );
    }

    private function handleOriginalEntityType(Job $job): void
    {
        if (!$job->blueprint->storeOriginalClass) {
            return;
        }

        if ($job->blueprint->storeRequestingOriginalClassStorage !== $job->blueprint->name) {
            return;
        }

        foreach ($this->originalClassStorageStrategy->buildStoreActions($job->blueprint, $job->directory) as $query) {
            $job->actionSet[] = $query;
        }
    }
}
