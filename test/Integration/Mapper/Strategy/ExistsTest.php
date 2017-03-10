<?php
namespace ScriptFUSIONTest\Integration\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Exists;
use ScriptFUSION\Mapper\Strategy\IfElse;
use ScriptFUSION\Mapper\Strategy\IfExists;
use ScriptFUSION\Mapper\Strategy\Strategy;

final class ExistsTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration;

    public function testPathString()
    {
        $exists = (new Exists('0->1'))->setMapper(new Mapper);

        self::assertFalse($exists([]));
        self::assertFalse($exists(['a']));
        self::assertFalse($exists([['a']]));
        self::assertTrue($exists([['a','b']]));
    }

    public function testPathArray()
    {
        $exists = (new Exists(['0', '1']))->setMapper(new Mapper);

        self::assertFalse($exists([]));
        self::assertFalse($exists(['a']));
        self::assertFalse($exists([['a']]));
        self::assertTrue($exists([['a','b']]));
    }

    public function testPathStrategy()
    {
        $exists = (new Exists(
            \Mockery::mock(Strategy::class)
                ->shouldReceive('__invoke')
                ->andReturn(true, false, null)
                ->getMock()
        ))->setMapper(new Mapper);

        self::assertTrue($exists([]));
        self::assertTrue($exists([]));
        self::assertFalse($exists([]));
    }
}
