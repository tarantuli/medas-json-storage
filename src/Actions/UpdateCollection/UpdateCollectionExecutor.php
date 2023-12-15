<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\UpdateCollection;

use Medas\Core\Attributes\Service;
use Medas\EntityManager\Entities\IdValue;
use Medas\JsonStorage\Data\{DataManager, FileData};

#[Service]
readonly class UpdateCollectionExecutor
{
    public function __construct(
        private DataManager $dataManager,
        private IdValue     $idValue,
    )
    {
    }

    public function execute(UpdateCollection $action): void
    {
        $content = $this->dataManager->get($action->file);
        $key = $this->fetchKey($action, $content);
        $data = $content->getDatum($key);

        foreach ($action->values->getAdditions() as $addition) {
            $data[$action->name][] = $this->idValue->fromEntity($addition);
        }

        foreach ($action->values->getDeletions() as $deletion) {
            unset($data[$action->name][array_search($this->idValue->fromEntity($deletion), $data[$action->name])]);
        }

        $content->setDatum($key, $data);
    }

    private function fetchKey(UpdateCollection $action, FileData $content): mixed
    {
        $entityReflector = new \ReflectionClass($action->entity);

        return $entityReflector->getProperty($content->keyName)->getValue($action->entity);
    }
}
