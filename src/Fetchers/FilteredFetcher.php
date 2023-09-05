<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Fetchers;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\IO\FileReader;
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
        private FileReader $reader,
    )
    {
    }

    public function fetch(Store $store, array $filters = []): RecordSetInterface
    {
        $content = $this->reader->read($store);

        $set = new RecordSet();

        foreach ($content['data'] as $record) {
            $matches = true;
            foreach ($filters as $key => $filter) {
                if ($record[$key] !== $filter) {
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
