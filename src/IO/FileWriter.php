<?php

declare(strict_types=1);

namespace Medas\JsonStorage\IO;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\Exceptions\FileNotFound;
use Medas\JsonStorage\Json;
use Medas\JsonStorage\StorageFile;

#[Service]
readonly class FileWriter
{
    public function __construct(
        private Json        $json,
        private PathBuilder $pathBuilder,
    )
    {
    }

    public function write(StorageFile $file, iterable $content): void
    {
        $path = $this->pathBuilder->build($file->storage(), $file->name());

        if (!file_exists($path)) {
            throw new FileNotFound($path);
        }

        file_put_contents($path, $this->json->encode($content));
    }
}
