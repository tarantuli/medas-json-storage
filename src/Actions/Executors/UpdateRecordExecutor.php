<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\Executors;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\{Actions\UpdateRecord, Data\DataManager};

#[Service]
readonly class UpdateRecordExecutor
{
    public function __construct(
        private DataManager $dataManager,
    )
    {
    }

    public function execute(UpdateRecord $action): void
    {
        $content = $this->dataManager->get($action->file);

        foreach ($content->data() as $key => $data) {
            foreach ($action->conditions as $condKey => $condition) {
                if ($data[$condKey] !== $condition) {
                    continue;
                }

                foreach ($action->updates as $field => $value) {
                    $data[$field] = $value;
                }

                $content->setDatum($key, $data);
            }
        }
    }
}
