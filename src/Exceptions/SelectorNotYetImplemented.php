<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Exceptions;

use Medas\Core\{Exceptions\BaseException, StringMaker};

class SelectorNotYetImplemented extends BaseException
{
    public function __construct(mixed $element)
    {
        parent::__construct(StringMaker::instance()->fromVariable($element));
    }

    public function pattern(): string
    {
        return 'selector element not yet implemented: %s';
    }
}
