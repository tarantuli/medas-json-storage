<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions\Executors;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\Actions\RecordAction;
use Medas\JsonStorage\IO\FileReader;
use Medas\JsonStorage\IO\FileWriter;

#[Service]
readonly class InsertExecutor
{
    public function __construct(
        private FileReader $reader,
        private FileWriter $writer,
    )
    {
    }

    public function execute(RecordAction $action): void
    {
        $content = $this->reader->read($action->file);
        $content['data'][] = $action->data;
        $this->writer->write($action->file, $content);
    }
}
