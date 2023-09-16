<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\Executors;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\Actions\GetRecord;

#[Service]
readonly class GetRecordExecutor
{

    public function execute(GetRecord $action): void
    {
        diedump($action);
    }
}
