<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Builders;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\Actions\CreateFileAction;
use Medas\JsonStorage\IO\PathBuilder;
use Medas\JsonStorage\Json;
use Medas\JsonStorage\StorageDirectory;
use Medas\StorageManager\Interfaces\Builders\CreateStoreBuilder as CreateStoreBuilderInterface;
use Medas\StorageManager\Interfaces\Storage;
use Medas\StorageManager\Structure\Blueprint;
use Medas\StorageManager\UnitOfWork\ActionSet;

#[Service]
readonly class CreateStoreBuilder implements CreateStoreBuilderInterface
{
    public function __construct(
        private Json        $json,
        private PathBuilder $pathBuilder,
    )
    {
    }

    public function build(Storage $storage, Blueprint $blueprint): ActionSet
    {
        /** @var StorageDirectory $storage */
        $path = $this->pathBuilder->build($storage, $blueprint->name());

        return \Medas\JsonStorage\Actions\ActionSet::fromAction(new CreateFileAction(
            $storage->name(),
            $path,
            $this->json->encode($this->createContent($blueprint))
        ));
    }

    public function createContent(Blueprint $blueprint): array
    {
        return [
            'key' => $blueprint->primaryIndex()?->fields()[0]?->name,
            'data' => [],
        ];
    }
}
