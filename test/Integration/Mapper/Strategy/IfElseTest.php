<?php
namespace ScriptFUSIONTest\Integration\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Exists;
use ScriptFUSION\Mapper\Strategy\IfElse;
use ScriptFUSION\Mapper\Strategy\Strategy;

final class IfElseTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration;

    public function testIfElse()
    {
        $ifExists = (
            new IfElse(
                \Mockery::mock(Strategy::class)
                    ->shouldReceive('__invoke')
                    ->andReturn(true, false, null)
                    ->getMock(),
                'foo',
                'bar'
            )
        )->setMapper(new Mapper);

        self::assertSame('foo', $ifExists([]));
        self::assertSame('bar', $ifExists([]));
        self::assertSame('bar', $ifExists([]));
    }

    public function testIfExistsElse()
    {
        $ifExists = (
        new IfElse(
            new Exists('0->1'),
            'foo',
            'bar'
        )
        )->setMapper(new Mapper);

        self::assertSame('foo', $ifExists([['a', 'b']]));
        self::assertSame('bar', $ifExists([['a']]));
        self::assertSame('bar', $ifExists(['a']));
        self::assertSame('bar', $ifExists([]));
    }

    public function testOnlyIf()
    {
        $ifElse = (
            new IfElse(
                \Mockery::mock(Strategy::class)
                    ->shouldReceive('__invoke')
                    ->andReturn(true, false, null)
                    ->getMock(),
                'foo'
            )
        )->setMapper(new Mapper);

        self::assertSame('foo', $ifElse([]));
        self::assertNull($ifElse([]));
        self::assertNull($ifElse([]));
    }

}
