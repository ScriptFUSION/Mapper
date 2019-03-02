<?php
namespace ScriptFUSIONTest\Integration\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Join;
use ScriptFUSION\Mapper\Strategy\Strategy;

final class JoinTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Tests that multiple expression are joined.
     */
    public function testJoin()
    {
        $join = (new Join(
            '-',
            'foo',
            \Mockery::mock(Strategy::class)->shouldReceive('__invoke')->andReturn('bar')->once()->getMock()
        ))->setMapper(new Mapper);

        self::assertSame('foo-bar', $join([]));
    }

    /**
     * Tests that a single expression evaluating to an array is joined.
     */
    public function testJoinArray()
    {
        $join = (new Join(
            '-',
            ['foo', 'bar']
        ))->setMapper(new Mapper);

        self::assertSame('foo-bar', $join([]));
    }
}
