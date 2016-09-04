<?php
namespace ScriptFUSIONTest;

use ScriptFUSION\Mapper\Strategy\Decorator;

final class DecoratorStub extends Decorator
{
    public function getStrategyPublic()
    {
        return $this->getStrategy();
    }
}
