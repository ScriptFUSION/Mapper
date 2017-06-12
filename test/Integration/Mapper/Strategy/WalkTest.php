<?php
namespace ScriptFUSIONTest\Integration\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Copy;
use ScriptFUSION\Mapper\Strategy\Walk;

final class WalkTest extends \PHPUnit_Framework_TestCase
{
    public function testWalkFixedPath()
    {
        $walk = (new Walk(['foo' => ['bar' => 'baz']], 'foo->bar'))
            ->setMapper(new Mapper);

        self::assertSame('baz', $walk([]));
    }

    public function testWalkStrategyPath()
    {
        $walk = (new Walk(['bar' => 'baz'], new Copy('foo')))
            ->setMapper(new Mapper);

        self::assertSame('baz', $walk(['foo' => 'bar']));
    }
}
