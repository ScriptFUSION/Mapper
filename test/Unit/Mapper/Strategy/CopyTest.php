<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use ScriptFUSION\Mapper\Strategy\Copy;

final class CopyTest extends \PHPUnit_Framework_TestCase
{
    public function testFalsyPathComponent()
    {
        $copy = new Copy('0->1');

        self::assertSame('bar', $copy([['foo', 'bar']]));
    }

    public function testEmptyPath()
    {
        $copy = new Copy('');

        self::assertNull($copy('foo'));
    }

    public function testNonexistentPath()
    {
        $copy = new Copy('foo->bar');

        self::assertNull($copy([]));
        self::assertNull($copy(['foo' => ['bar']]));
        self::assertNull($copy(['foo' => 'bar']));
    }
}
