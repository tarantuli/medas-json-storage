<?php

declare(strict_types=1);

namespace Medas\JsonStorage\ConfigOptions;

use Medas\Core\AsSingleton;
use Medas\Core\Interfaces\{ConfigGroup, ConfigOption};

class DirectoryPath implements ConfigOption
{
    use AsSingleton;

    public function group(): ConfigGroup
    {
        return JsonGroup::instance();
    }

    public function name(): string
    {
        return 'directory-path';
    }

    public function description(): string
    {
        return 'The path to the JSON directory';
    }

    public function isValid(mixed $value): bool
    {
        return is_string($value);
    }

    public function hasDefault(): bool
    {
        return false;
    }

    public function default(): null
    {
        return null;
    }
}
