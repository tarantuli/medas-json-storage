<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Builders;

use Medas\Core\Attributes\Service;
use Medas\FileBuilder\PhpClass\MethodDefinition;
use Medas\Json\{JsonEncoder, Settings};
use Medas\JsonStorage\Actions\CreateStore\{CreateStore, CreateStoreBuilder};
use Medas\JsonStorage\IO\PathBuilder;
use Medas\StorageManager\{
    Interfaces\Storage,
    Migrations\MigrationBuilder as MigrationBuilderInterface,
    Structure\Blueprint,
    UnitOfWork\ActionSet
};

#[Service]
readonly class MigrationBuilder implements MigrationBuilderInterface
{
    public function __construct(
        private PathBuilder        $pathBuilder,
        private JsonEncoder        $encoder,
        private CreateStoreBuilder $createStoreBuilder,
    )
    {
    }

    public function build(
        Storage          $storage,
        Blueprint        $expectedStructure,
        MethodDefinition $migrateMethod,
        MethodDefinition $undoMethod
    ): bool
    {
        $path = $this->pathBuilder->build($storage, $expectedStructure->name);

        if (file_exists($path)) {
            return false;
        }

        $actionClass = CreateStore::class;
        $actions = $this->buildActions($storage, $expectedStructure);

        foreach ($actions as $action) {
            /** @var CreateStore $action */
            $storageName = addcslashes($storage->name(), '"');
            $path = addcslashes($action->path, '"\\');
            $content = $this->encoder->encode($action->content, new Settings(true));
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
