<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\Strategy\Filter;
use ScriptFUSIONTest\MockFactory;

final class FilterTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testDefaultCallback()
    {
        $filter = new Filter(null);
        $filter->setMapper(MockFactory::mockMapper([null, 0, '0', null]));

        self::assertSame([1 => 0, 2 => '0'], $filter([]));
    }

    public function testFilterByValue()
    {
        $filter = new Filter(null, function ($value) {
            return $value['foo'] === 'bar';
        });

        $filter->setMapper(MockFactory::mockMapper([
            $bar = ['foo' => 'bar'],
            ['foo' => 'baz'],
        ]));

        self::assertSame([$bar], $filter([]));
    }

    public function testFilterByKey()
    {
        $filter = new Filter(null, function ($_, $key) {
            return $key % 2;
        });
        $filter->setMapper(MockFactory::mockMapper(range('a', 'e')));

        self::assertSame([1 => 'b', 3 => 'd'], $filter([]));
    }

    public function testContextPassed()
    {
        $filter = new Filter(null, function ($_, $__, $context) {
            self::assertSame('foo', $context);
        });
        $filter->setMapper(MockFactory::mockMapper(['bar']));

        $filter([], 'foo');
    }

    public function testNonArray()
    {
        $filter = new Filter(null);

        $filter->setMapper(MockFactory::mockMapper(null));
        self::assertNull($filter([]));

        $filter->setMapper(MockFactory::mockMapper((object)['foo' => 'bar']));
        self::assertNull($filter([]));
    }
}
