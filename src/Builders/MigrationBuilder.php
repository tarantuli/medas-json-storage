<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Builders;

use Medas\Core\Attributes\Service;
use Medas\FileBuilder\PhpClass\MethodDefinition;
use Medas\JsonStorage\Actions\CreateFile;
use Medas\JsonStorage\IO\PathBuilder;
use Medas\JsonStorage\Json;
use Medas\StorageManager\Interfaces\Storage;
use Medas\StorageManager\Migrations\MigrationBuilder as MigrationBuilderInterface;
use Medas\StorageManager\Structure\Blueprint;
use Medas\StorageManager\UnitOfWork\ActionSet;

#[Service]
readonly class MigrationBuilder implements MigrationBuilderInterface
{
    public function __construct(
        private PathBuilder        $pathBuilder,
        private Json               $json,
        private CreateStoreBuilder $createStoreBuilder,
    )
    {
    }

    public function build(Storage $storage, Blueprint $expectedStructure, MethodDefinition $migrateMethod, MethodDefinition $undoMethod): bool
    {
        $path = $this->pathBuilder->build($storage, $expectedStructure->name);

        if (file_exists($path)) {
            return false;
        }

        $actionClass = CreateFile::class;

        $actions = $this->buildActions($storage, $expectedStructure);

        foreach ($actions as $action) {
            /** @var CreateFile $action */
            $storageName = addcslashes($storage->name(), '"');
            $path = addcslashes($action->path, '"\\');
            $content = $this->json->encode($action->content);

            $migrateMethod->body .= <<<PHP
\$unitOfWork->addAction(new \\$actionClass(
    "$storageName",
    "$path",
    $content
));
PHP;
        }

        return true;
    }

    public function buildActions(Storage $storage, Blueprint $blueprint): ActionSet
    {
        return $this->createStoreBuilder->build($storage, $blueprint);
    }
}
