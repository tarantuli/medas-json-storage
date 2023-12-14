<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Builders;

use Medas\Core\Attributes\{ConfigValue, Service};
use Medas\JsonStorage\{Actions\CreateFile, IO\PathBuilder, Json, StorageDirectory};
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
        private Json                         $json,
        private PathBuilder                  $pathBuilder,

        #[ConfigValue(DefaultStrategy::class)]
        private OriginalClassStorageStrategy $originalClassStorageStrategy,
    )
    {
    }

    public function build(Storage $storage, Blueprint $blueprint): ActionSet
    {
        /** @var StorageDirectory $storage */
        $job = new CreateStoreBuilder\Job($storage, $blueprint);

        $this->addBasicStore($job);
        $this->handleOriginalEntityType($job);

        return $job->actionSet;
    }

    private function createContent(Blueprint $blueprint): array
    {
        $fieldNames = [];

        foreach ($blueprint->fields as $field) {
            if ($field->store === $blueprint->name) {
                $fieldNames[] = $field->name;
            }
        }

        return [
            'keyName' => $blueprint->primaryIndex()?->fields()[0]?->name,
            'fieldNames' => $fieldNames,
            'data' => [],
        ];
    }

    private function addBasicStore(CreateStoreBuilder\Job $job): void
    {
        $path = $this->pathBuilder->build($job->directory, $job->blueprint->name);

        $job->actionSet[] = new CreateFile(
            $job->directory->name(),
            $path,
            $this->json->encode($this->createContent($job->blueprint))
        );
    }

    private function handleOriginalEntityType(CreateStoreBuilder\Job $job): void
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
