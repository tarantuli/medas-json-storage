<?php

declare(strict_types=1);

namespace Medas\JsonStorage\Actions;

use Medas\EntityManager\Selector\Selector;
use Medas\StorageManager\Interfaces\ActionBuilder;
use Medas\StorageManager\Structure\Blueprint;
use Medas\StorageManager\UnitOfWork\{Action, ActionCollection};

class JsonActionBuilder implements ActionBuilder
{
    public function createStore(Blueprint $blueprint): ActionCollection
    {
        // TODO: Implement createStore() method.
    }

    public function fromSelector(Selector $selector, array $arguments): Action
    {
        // TODO: Implement fromSelector() method.
    }
}
