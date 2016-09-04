<?php
namespace ScriptFUSIONTest\Unit\Mapper;

use ScriptFUSION\Mapper\Strategy\Strategy;
use ScriptFUSIONTest\DecoratorStub;

final class DecoratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetStrategy()
    {
        $decorator = new DecoratorStub($strategy = \Mockery::mock(Strategy::class));

        self::assertSame($strategy, $decorator->getStrategyPublic());
    }
}
