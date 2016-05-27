<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use ScriptFUSION\Mapper\Strategy\Filter;

final class FilterTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration, MockMapper;

    public function testDefaultCallback()
    {
        $filter = new Filter(null);
        $filter->setMapper($this->mockMapper([null, 0, "0", null]));

        self::assertEquals([0, "0"], array_values($filter([])));
    }

    public function testCustomCallback()
    {
        $filter = new Filter(null, function ($value) {
            return $value['foo'] === 'bar';
        });

        $filter->setMapper($this->mockMapper([
            [
                'foo' => 'bar',
            ],
            [
                'foo' => 'baz',
            ],
        ]));

        self::assertEquals([['foo' => 'bar']], $filter([]));
    }

    public function testNonArray()
    {
        $filter = new Filter(null);

        $filter->setMapper($this->mockMapper(null));
        self::assertNull($filter([]));

        $filter->setMapper($this->mockMapper((object)['foo' => 'bar']));
        self::assertNull($filter([]));
    }
}
