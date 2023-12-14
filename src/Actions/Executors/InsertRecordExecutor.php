<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\Executors;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\{Actions\InsertRecord, Data\DataManager};
use Medas\StorageManager\Entities\LastInsertIdPlaceholder;

#[Service]
readonly class InsertRecordExecutor
{
    public function __construct(
        private DataManager $dataManager,
    )
    {
    }

    public function execute(InsertRecord $action, int|null $lastInsertId): void
    {
        $content = $this->dataManager->get($action->file);

        if ($content->keyName === null || !isset($action->data[$content->keyName])) {
            $key = $content->dataCount() + 1;
        }
        else {
            if ($action->data[$content->keyName] instanceof LastInsertIdPlaceholder) {
                $key = $lastInsertId;
                $action->data[$content->keyName] = $key;
            }
            else {
                $key = $action->data[$content->keyName];
            }
        }

        $action->insertId = $key;

        $content->setDatum($key, $action->data);
    }
}
