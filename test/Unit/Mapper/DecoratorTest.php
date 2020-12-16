<?php
namespace ScriptFUSIONTest\Unit\Mapper;

use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\Strategy\Strategy;
use ScriptFUSIONTest\DecoratorStub;

final class DecoratorTest extends TestCase
{
    public function testGetStrategy()
    {
        $decorator = new DecoratorStub($strategy = \Mockery::mock(Strategy::class));

        self::assertSame($strategy, $decorator->getStrategyPublic());
    }
}
