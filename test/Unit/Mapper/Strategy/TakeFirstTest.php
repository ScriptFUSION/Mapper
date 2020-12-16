<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\Strategy\TakeFirst;
use ScriptFUSIONTest\MockFactory;

final class TakeFirstTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testTake1()
    {
        $takeFirst = new TakeFirst(null);
        $takeFirst->setMapper(MockFactory::mockMapper(['foo']));

        self::assertSame('foo', $takeFirst([]));
    }

    public function testTake2()
    {
        $takeFirst = new TakeFirst(null, 2);
        $takeFirst->setMapper(MockFactory::mockMapper([['foo']]));

        self::assertSame('foo', $takeFirst([]));
    }

    public function testNullResult()
    {
        $takeFirst = new TakeFirst(null);
        $takeFirst->setMapper(MockFactory::mockMapper(null));

        self::assertNull($takeFirst([]));
    }
}
