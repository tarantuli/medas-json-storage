<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Fetchers;

use Medas\Core\Attributes\Service;
use Medas\EntityManager\MetaData\Property;
use Medas\JsonStorage\Data\{DataManager, FileData};
use Medas\StorageManager\Interfaces\{
    Fetchers\CollectionRecordFetcher as CollectionRecordFetcherInterface,
    Store
};

#[Service]
readonly class CollectionRecordFetcher implements CollectionRecordFetcherInterface
{
    public function __construct(
        private DataManager $dataManager,
    )
    {
    }

    public function fetch(Store $store, object $entity, Property $property): iterable
    {
        $fileData = $this->dataManager->get($store);
        $key = $this->fetchKey($entity, $fileData);
        $data = $fileData->getDatum($key);

        foreach ($data[$property->name] as $id) {
            yield ['value' => $id];
        }
    }

    private function fetchKey(object $entity, FileData $fileData): mixed
    {
        $entityReflector = new \ReflectionClass($entity);

        return $entityReflector->getProperty($fileData->keyName)->getValue($entity);
    }
}
