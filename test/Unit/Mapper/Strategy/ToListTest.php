<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\Strategy\ToList;
use ScriptFUSIONTest\MockFactory;

final class ToListTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var ToList */
    private $toList;

    protected function setUp(): void
    {
        $this->toList = new ToList(null);
    }

    public function testMap()
    {
        $this->toList->setMapper(MockFactory::mockMapper(
            $map = [
                'foo' => 'bar',
            ]
        ));

        self::assertSame([$map], $this->toList($map));
    }

    public function testList()
    {
        $this->toList->setMapper(MockFactory::mockMapper(
            $map = [
                'foo',
                'bar',
            ]
        ));

        self::assertSame($map, $this->toList($map));
    }

    public function testScalar()
    {
        $this->toList->setMapper(MockFactory::mockMapper('foo'));

        $this->toList(['foo']);
    }

    private function toList()
    {
        return call_user_func_array($this->toList, func_get_args());
    }
}
