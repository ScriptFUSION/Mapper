<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Merge;

final class MergeTest extends TestCase
{
    public function test()
    {
        /** @var Merge $merge */
        $merge = (new Merge('foo', 'bar'))->setMapper(new Mapper);

        self::assertSame(['foo', 'bar'], $merge([]));
    }
}
