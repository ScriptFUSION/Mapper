<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use ScriptFUSION\Mapper\Strategy\Copy;

final class CopyTest extends \PHPUnit_Framework_TestCase
{
    public function testFalsyPathComponentString()
    {
        $copy = new Copy('0');
        self::assertSame('foo', $copy(['foo']));

        $copy = new Copy('0->1');
        self::assertSame('bar', $copy([['foo', 'bar']]));
    }

    public function testFalsyPathComponentArray()
    {
        $copy = new Copy([0]);
        self::assertSame('foo', $copy(['foo']));

        $copy = new Copy([0, 1]);
        self::assertSame('bar', $copy([['foo', 'bar']]));
    }

    public function testEmptyPathString()
    {
        $copy = new Copy('');

        self::assertNull($copy(['foo']));
    }

    public function testEmptyPathArray()
    {
        $copy = new Copy([]);

        self::assertNull($copy(['foo']));
    }

    public function testNonexistentPathString()
    {
        $copy = new Copy('foo->bar');

        self::assertNull($copy([]));
        self::assertNull($copy(['foo' => ['bar']]));
        self::assertNull($copy(['foo' => 'bar']));
    }

    public function testNonexistentPathArray()
    {
        $copy = new Copy(['foo', 'bar']);

        self::assertNull($copy([]));
        self::assertNull($copy(['foo' => ['bar']]));
        self::assertNull($copy(['foo' => 'bar']));
    }
}
