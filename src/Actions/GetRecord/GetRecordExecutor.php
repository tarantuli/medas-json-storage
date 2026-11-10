<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\GetRecord;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\{Fetchers\FilteredFetcher, RecordSet};

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
        /** @var RecordSet $recordSet */
        $recordSet = $this->filteredFetcher->fetch($action->files[0], $action->criteria);

        if ($action->slice !== null) {
            $recordSet = $recordSet->slice($action->slice->from, $action->slice->count);
        }

        $action->recordSet = $recordSet;
    }
}
