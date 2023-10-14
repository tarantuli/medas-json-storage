<?php

declare(strict_types=1);

namespace Medas\JsonStorage\IO;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\Data\FileData;
use Medas\JsonStorage\Exceptions\FileNotFound;
use Medas\JsonStorage\Json;

#[Service]
readonly class FileWriter
{
    public function __construct(
        private Json        $json,
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

        file_put_contents($path, $this->json->encode($data->content()));
    }
}
