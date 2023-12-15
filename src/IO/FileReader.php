<?php

declare(strict_types=1);

namespace Medas\JsonStorage\IO;

use JetBrains\PhpStorm\ArrayShape;
use Medas\Core\Attributes\Service;
use Medas\Json\JsonEncoder;
use Medas\JsonStorage\{Exceptions\FileNotFound, StorageFile};

#[Service]
readonly class FileReader
{
    public function __construct(
        private JsonEncoder $encoder,
        private PathBuilder $pathBuilder,
    )
    {
    }

    #[ArrayShape(['keyName' => 'string', 'fieldNames' => 'array', 'defaults' => 'array', 'data' => 'array'])]
    public function read(StorageFile $file): iterable
    {
        $path = $this->pathBuilder->build($file->storage(), $file->name());

        if (!file_exists($path)) {
            throw new FileNotFound($path);
        }

        return $this->encoder->decode(file_get_contents($path));
    }
}
