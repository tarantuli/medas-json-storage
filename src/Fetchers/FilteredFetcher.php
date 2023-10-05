<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Fetchers;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\Data\DataManager;
use Medas\JsonStorage\RecordSet;
use Medas\StorageManager\Entities\Record;
use Medas\StorageManager\Interfaces\Fetchers\FilteredFetcher as FilteredFetcherInterface;
use Medas\StorageManager\Interfaces\Record as RecordInterface;
use Medas\StorageManager\Interfaces\RecordSet as RecordSetInterface;
use Medas\StorageManager\Interfaces\Store;

#[Service]
readonly class FilteredFetcher implements FilteredFetcherInterface
{
    public function __construct(
        private DataManager $dataManager,
    )
    {
    }

    public function fetch(Store $store, array $filters = []): RecordSetInterface
    {
        $fileData = $this->dataManager->get($store);
        $set = new RecordSet();

        if ($filters && $fileData->keyName === array_keys($filters)[0]) {
            if ($record = $fileData->getDatum($filters[$fileData->keyName])) {
                $set[] = new Record($record);
            }

            return $set;
        }

        foreach ($fileData->data() as $record) {
            $matches = true;
            foreach ($filters as $property => $filter) {
                if ($record[$property] !== $filter) {
                    $matches = false;
                    break;
                }
            }

            if ($matches) {
                $set[] = new Record($record);
            }
        }

        return $set;
    }

    public function fetchOne(Store $store, array $filters = []): RecordInterface|null
    {
        $records = $this->fetch($store, $filters);

        return $records->fetchRecord();
    }
}
