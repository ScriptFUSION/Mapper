<?php
namespace ScriptFUSIONTest\Integration\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Either;
use ScriptFUSION\Mapper\Strategy\Strategy;

final class EitherTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var Either */
    private $either;

    /** @var MockInterface */
    private $strategy;

    protected function setUp(): void
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
