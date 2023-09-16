<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\Executors;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\Actions\InsertRecord;
use Medas\JsonStorage\IO\{FileReader, FileWriter};
use Medas\StorageManager\Entities\LastInsertIdPlaceholder;

#[Service]
readonly class InsertRecordExecutor
{
    public function __construct(
        private FileReader $reader,
        private FileWriter $writer,
    )
    {
    }

    public function execute(InsertRecord $action): void
    {
        $content = $this->reader->read($action->file);

        $keyProperty = $content['key'];

        if ($keyProperty === null || !isset($action->data[$keyProperty])) {
            $key = count($content['data']) + 1;
        }
        else {
            if ($action->data[$keyProperty] instanceof LastInsertIdPlaceholder) {
                $key = count($content['data']) + 1;
                $action->data[$keyProperty] = $key;
            }
            else {
                $key = $action->data[$keyProperty];
            }
        }

        $action->insertId = $key;
        $content['data'][$key] = $action->data;
        $this->writer->write($action->file, $content);
    }
}
