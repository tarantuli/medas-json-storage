<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\DeleteRecord;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\Data\DataManager;

#[Service]
readonly class DeleteRecordExecutor
{
    public function __construct(
        private DataManager $dataManager,
    )
    {
    }

    public function execute(DeleteRecord $action): void
    {
        $content = $this->dataManager->get($action->file);

        foreach ($content->data() as $key => $data) {
            foreach ($action->conditions as $condKey => $condition) {
                if ($data[$condKey] !== $condition) {
                    continue;
                }

                $content->unsetDatum($key);
            }
        }
    }
}
