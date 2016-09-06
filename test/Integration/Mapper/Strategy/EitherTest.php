<?php
namespace ScriptFUSIONTest\Integration\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Either;
use ScriptFUSION\Mapper\Strategy\Strategy;

final class EitherTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var Either */
    private $either;

    /** @var MockInterface */
    private $strategy;

    protected function setUp()
    {
        $this->either = (new Either($this->strategy = \Mockery::spy(Strategy::class), 'bar'))->setMapper(new Mapper);
    }

    public function testNonNullStrategy()
    {
        $either = $this->either;
        $this->strategy->shouldReceive('__invoke')->andReturn('foo');

        self::assertSame('foo', $either([]));
    }

    public function testNullStrategy()
    {
        $either = $this->either;

        self::assertSame('bar', $either([]));
    }
}
