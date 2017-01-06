<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use ScriptFUSION\Mapper\Strategy\Filter;
use ScriptFUSIONTest\MockFactory;

final class FilterTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration;

    public function testDefaultCallback()
    {
        $filter = new Filter(null);
        $filter->setMapper(MockFactory::mockMapper([null, 0, "0", null]));

        self::assertEquals([0, "0"], array_values($filter([])));
    }

    public function testCustomCallback()
    {
        $filter = new Filter(null, function ($value) {
            return $value['foo'] === 'bar';
        });

        $filter->setMapper(MockFactory::mockMapper([
            [
                'foo' => 'bar',
            ],
            [
                'foo' => 'baz',
            ],
        ]));

        self::assertEquals([['foo' => 'bar']], $filter([]));
    }

    public function testContextPassed()
    {
        $filter = new Filter(null, function ($_, $context) {
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
