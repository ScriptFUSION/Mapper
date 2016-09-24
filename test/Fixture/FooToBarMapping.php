<?php
namespace ScriptFUSIONTest\Fixture;

use ScriptFUSION\Mapper\Mapping;
use ScriptFUSION\Mapper\Strategy\Copy;

class FooToBarMapping extends Mapping
{
    protected function createMapping()
    {
        return ['bar' => new Copy('foo')];
    }
}
