<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use ScriptFUSION\Mapper\Strategy\Flatten;
use ScriptFUSIONTest\MockFactory;

final class FlattenTest extends \PHPUnit_Framework_TestCase
{
    public function testValues()
    {
        /** @var Flatten $flatten */
        $flatten = (new Flatten(null))
            ->ignoreKeys()
            ->setMapper(MockFactory::mockMapper(['foo', ['bar', ['baz']]]))
        ;

        self::assertSame(['foo', 'bar', 'baz'], $flatten([]));
    }

    public function testUniqueKeys()
    {
        /** @var Flatten $flatten */
        $flatten = (new Flatten(null))
            ->setMapper(MockFactory::mockMapper(['foo' => 'foo', ['bar' => 'bar', ['baz' => 'baz']]]))
        ;

        self::assertSame(['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz'], $flatten([]));
    }

    public function testDuplicateKeys()
    {
        /** @var Flatten $flatten */
        $flatten = (new Flatten(null))
            ->setMapper(MockFactory::mockMapper(['foo' => 'foo', ['bar' => 'bar', ['bar' => 'baz']]]))
        ;
        self::assertSame(['foo' => 'foo', 'bar' => 'baz'], $flatten([]));

        $flatten->setMapper(MockFactory::mockMapper(['foo' => 'foo', ['foo' => 'bar']]));
        self::assertSame(['foo' => 'bar'], $flatten([]));
    }
}
