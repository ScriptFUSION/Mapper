<?php
namespace ScriptFUSIONTest\Functional;

use ScriptFUSION\Mapper\AnonymousMapping;
use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Merge;

final class StrategyBasedMappingTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        self::assertSame(
            ['foo' => 'foo', 'bar' => 'bar'],
            (new Mapper)->map(
                [],
                new AnonymousMapping(
                    new Merge(
                        ['foo' => 'foo'],
                        ['bar' => 'bar']
                    )
                )
            )
        );
    }
}
