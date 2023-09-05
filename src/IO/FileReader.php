<?php

declare(strict_types=1);

namespace Medas\JsonStorage\IO;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\Exceptions\FileNotFound;
use Medas\JsonStorage\Json;
use Medas\JsonStorage\StorageFile;

#[Service]
readonly class FileReader
{
    public function __construct(
        private Json        $json,
        private PathBuilder $pathBuilder,
    )
    {
    }

    public function read(StorageFile $file): iterable
    {
        $path = $this->pathBuilder->build($file->storage(), $file->name());

        if (!file_exists($path)) {
            throw new FileNotFound($path);
        }

        return $this->json->decode(file_get_contents($path));
    }
}
