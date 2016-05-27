<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use ScriptFUSION\Mapper\Strategy\ToList;

final class ToListTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration, MockMapper;

    /** @var ToList */
    private $toList;

    protected function setUp()
    {
        $this->toList = new ToList(null);
    }

    public function testMap()
    {
        $this->toList->setMapper($this->mockMapper(
            $map = [
                'foo' => 'bar',
            ]
        ));

        self::assertSame([$map], $this->toList($map));
    }

    public function testList()
    {
        $this->toList->setMapper($this->mockMapper(
            $map = [
                'foo',
                'bar',
            ]
        ));

        self::assertSame($map, $this->toList($map));
    }

    public function testScalar()
    {
        $this->toList->setMapper($this->mockMapper('foo'));

        $this->toList(['foo']);
    }

    private function toList()
    {
        return call_user_func_array($this->toList, func_get_args());
    }
}
