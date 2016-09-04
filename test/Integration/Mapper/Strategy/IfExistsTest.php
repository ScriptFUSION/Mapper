<?php
namespace ScriptFUSIONTest\Integration\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\IfExists;
use ScriptFUSION\Mapper\Strategy\Strategy;

final class IfExistsTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration;

    public function testIfElse()
    {
        /** @var IfExists $ifExists */
        $ifExists = (
            new IfExists(
                \Mockery::mock(Strategy::class)
                    ->shouldReceive('__invoke')
                    ->andReturn(true, false, null)
                    ->getMock(),
                'foo',
                'bar'
            )
        )->setMapper(new Mapper);

        self::assertSame('foo', $ifExists([]));
        self::assertSame('foo', $ifExists([]));
        self::assertSame('bar', $ifExists([]));
    }

    public function testOnlyIf()
    {
        /** @var IfExists $ifExists */
        $ifExists = (new IfExists(
            \Mockery::mock(Strategy::class)
                ->shouldReceive('__invoke')
                ->andReturn(true, null)
                ->getMock(),
            'foo'
        ))->setMapper(new Mapper);

        self::assertSame('foo', $ifExists([]));
        self::assertNull($ifExists([]));
    }
}
