<?php

declare(strict_types=1);

namespace Medas\JsonStorage\IO;

use Medas\Core\Attributes\Service;
use Medas\Json\{JsonEncoder, Settings};
use Medas\JsonStorage\{Data\FileData, Exceptions\FileNotFound};

#[Service]
readonly class FileWriter
{
    public function __construct(
        private JsonEncoder $encoder,
        private PathBuilder $pathBuilder,
    )
    {
    }

    public function write(FileData $data): void
    {
        $path = $this->pathBuilder->build($data->file->storage(), $data->file->name());

        if (!file_exists($path)) {
            throw new FileNotFound($path);
        }

        file_put_contents($path, $this->encoder->encode($data->content(), new Settings(true)));
    }
}
