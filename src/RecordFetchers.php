<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\Core\Attributes\Service;
use Medas\StorageManager\Interfaces\{Fetchers, RecordFetchers as RecordFetchersInterface};

#[Service]
readonly class RecordFetchers implements RecordFetchersInterface
{
    public function __construct(
        private Fetchers\FilteredFetcher $filteredFetcher,
    )
    {
    }

    public function filteredFetcher(): Fetchers\FilteredFetcher
    {
        return $this->filteredFetcher;
    }

    public function collectionRecordFetcher(): Fetchers\CollectionRecordFetcher
    {
        // TODO: Implement collectionRecordFetcher() method.
    }
}
