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

        self::assertSame([$map], $this->toList());
    }

    public function testList()
    {
        $this->toList->setMapper(MockFactory::mockMapper(
            $map = [
                'foo',
                'bar',
            ]
        ));

        self::assertSame($map, $this->toList());
    }

    public function testScalar()
    {
        $this->toList->setMapper(MockFactory::mockMapper('foo'));

        self::assertSame(['foo'], $this->toList());
    }

    private function toList()
    {
        return ($this->toList)([]);
    }
}
