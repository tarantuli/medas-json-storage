<?php

declare(strict_types=1);

namespace Medas\JsonStorage\IO;

use Medas\Core\Attributes\Service;
use Medas\JsonStorage\StorageDirectory;

#[Service]
class PathBuilder
{
    public function build(StorageDirectory $storage, string $name): string
    {
        return $storage->directory . DIRECTORY_SEPARATOR . $name . '.json';
    }
}
