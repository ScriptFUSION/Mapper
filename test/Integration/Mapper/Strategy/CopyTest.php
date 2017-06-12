<?php
namespace ScriptFUSIONTest\Integration\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Copy;

final class CopyTest extends \PHPUnit_Framework_TestCase
{
    public function testWalkFixedPath()
    {
        $copy = (new Copy('foo->bar', ['foo' => ['bar' => 'baz']]))
            ->setMapper(new Mapper);

        self::assertSame('baz', $copy([]));
    }

    public function testWalkStrategyPath()
    {
        $copy = (new Copy(new Copy('foo'), ['bar' => 'baz']))
            ->setMapper(new Mapper);

        self::assertSame('baz', $copy(['foo' => 'bar']));
    }
}
