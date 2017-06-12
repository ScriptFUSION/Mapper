<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use ScriptFUSION\Mapper\Strategy\Copy;
use ScriptFUSIONTest\MockFactory;

final class CopyTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration;

    public function testFalsyPathComponentString()
    {
        $copy = self::createStrategy('0');
        self::assertSame('foo', $copy(['foo']));

        $copy = self::createStrategy('0->1');
        self::assertSame('bar', $copy([['foo', 'bar']]));
    }

    public function testFalsyPathComponentArray()
    {
        $copy = self::createStrategy([0]);
        self::assertSame('foo', $copy(['foo']));

        $copy = self::createStrategy([0, 1]);
        self::assertSame('bar', $copy([['foo', 'bar']]));
    }

    public function testNullRecord()
    {
        $copy = self::createStrategy(0);

        self::assertNull($copy(null));
    }

    public function testNullPath()
    {
        $copy = self::createStrategy(null);

        self::assertNull($copy([]));
    }

    public function testEmptyPathString()
    {
        $copy = self::createStrategy('');

        self::assertNull($copy(['foo']));
    }

    public function testEmptyPathArray()
    {
        $copy = self::createStrategy([]);

        self::assertNull($copy(['foo']));
    }

    public function testNonexistentPathString()
    {
        $copy = self::createStrategy('foo->bar');

        self::assertNull($copy([]));
        self::assertNull($copy(['foo' => ['bar']]));
        self::assertNull($copy(['foo' => 'bar']));
    }

    public function testNonexistentPathArray()
    {
        $copy = self::createStrategy(['foo', 'bar']);

        self::assertNull($copy([]));
        self::assertNull($copy(['foo' => ['bar']]));
        self::assertNull($copy(['foo' => 'bar']));
    }

    private static function createStrategy($path)
    {
        return (new Copy($path))->setMapper(MockFactory::mockMapper($path));
    }
}
