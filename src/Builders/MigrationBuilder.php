<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Builders;

use Medas\Core\Attributes\Service;
use Medas\FileBuilder\PhpClass\MethodDefinition;
use Medas\JsonStorage\Actions\CreateFileAction;
use Medas\JsonStorage\IO\PathBuilder;
use Medas\JsonStorage\Json;
use Medas\JsonStorage\StorageDirectory;
use Medas\StorageManager\Interfaces\Storage;
use Medas\StorageManager\Migrations\MigrationBuilder as MigrationBuilderInterface;
use Medas\StorageManager\Structure\Blueprint;

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
        /** @var StorageDirectory $storage */
        $path = $this->pathBuilder->build($storage, $expectedStructure->name());

        if (file_exists($path)) {
            return false;
        }

        $actionClass = CreateFileAction::class;

        $content = $this->createStoreBuilder->createContent($expectedStructure);

        $storageName = addcslashes($storage->name(), '"');
        $path = addcslashes($path, '"\\');
        $content = addcslashes($this->json->encode($content), '\'');

        $migrateMethod->body .= <<<PHP
\$unitOfWork->addAction(new \\$actionClass(
    "$storageName",
    "$path",
    '$content'
));
PHP;

        return true;
    }
}
