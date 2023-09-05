<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions;

enum Type
{
    case InsertRecord;
    case UpdateRecord;
    case DeleteRecord;
    case CreateFile;
}
