<?php
namespace ScriptFUSIONTest\Integration\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Join;
use ScriptFUSION\Mapper\Strategy\Strategy;

final class JoinTest extends \PHPUnit_Framework_TestCase
{
    public function testJoin()
    {
        $join = (new Join(
            '-',
            'foo',
            \Mockery::mock(Strategy::class)->shouldReceive('__invoke')->andReturn('bar')->once()->getMock()
        ))->setMapper(new Mapper);

        self::assertSame('foo-bar', $join([]));
    }
}
