<?php
namespace ScriptFUSIONTest\Integration\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Copy;

final class CopyTest extends \PHPUnit_Framework_TestCase
{
    public function testFixedPath()
    {
        $copy = (new Copy('foo->bar', ['foo' => ['bar' => 'baz']]))
            ->setMapper(new Mapper);

        self::assertSame('baz', $copy([]));
    }

    public function testStrategyPath()
    {
        $copy = (new Copy(new Copy('foo'), ['bar' => 'baz']))
            ->setMapper(new Mapper);

        self::assertSame('baz', $copy(['foo' => 'bar']));
    }

    /**
     * Tests that null is returned when the data parameter does not resolve to an array type.
     */
    public function testInvalidData()
    {
        $copy = (new Copy('foo', 'bar'))
            ->setMapper(new Mapper);

        self::assertNull($copy(['foo' => 'bar']));
    }
}
