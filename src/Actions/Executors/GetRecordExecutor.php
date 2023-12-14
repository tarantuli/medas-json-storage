<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\Executors;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\{Actions\GetRecord, Fetchers\FilteredFetcher};

#[Service]
readonly class GetRecordExecutor
{
    public function __construct(
        private FilteredFetcher $filteredFetcher,
    )
    {
    }

    public function execute(GetRecord $action): void
    {
        $action->recordSet = $this->filteredFetcher->fetch($action->files[0], $action->criteria);
    }
}
