<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Migrations;

use Medas\Core\Attributes\Service;
use Medas\FileBuilder\PhpClass\MethodDefinition;
use Medas\JsonStorage\Actions\JsonAction;
use Medas\JsonStorage\Definitions\BlueprintBuilder;
use Medas\JsonStorage\JsonDirectory;
use Medas\StorageManager\Interfaces\Storage;
use Medas\StorageManager\Migrations\MigrationBuilder as MigrationBuilderInterface;
use Medas\StorageManager\Structure\Changes\ChangeFinder;
use Medas\StorageManager\Structure\EntityStructureFinder;
use Medas\StorageManager\UnitOfWork\Priority;

#[Service]
class MigrationBuilder implements MigrationBuilderInterface
{
    public function __construct(
        private readonly ChangeFinder          $changeFinder,
        private readonly EntityStructureFinder $entityStructureFinder,
        private readonly BlueprintBuilder      $blueprintBuilder,
    )
    {
    }

    public function build(Storage $storage, string $className, MethodDefinition $migrateMethod, MethodDefinition $undoMethod): bool
    {
        $actions = $this->buildActions($storage, $className);

        if (count($actions) === 0) {
            return false;
        }

        $actionClass = JsonAction::class;
        $priorityClass = Priority::class;

        foreach ($actions as $action) {
            $migrateMethod->body .= <<<PHP
            \$unitOfWork->addAction(new \\$actionClass(
                action: "$actionString",
                priority: \\$priorityClass::{$action->priority()->name}
            ));
        PHP;
        }

        return true;
    }

    private function buildActions(JsonDirectory $directory, string $className)
    {
        $expectedStructure = $this->entityStructureFinder->find($className);
        $existingStructure = $this->blueprintBuilder->build($directory->store($expectedStructure->name())->definition());

        $changes = $this->changeFinder->find($expectedStructure, $existingStructure);
        return $changes;
    }
}
