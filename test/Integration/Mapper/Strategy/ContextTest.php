<?php
namespace ScriptFUSIONTest\Integration\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Context;
use ScriptFUSION\Mapper\Strategy\Strategy;

final class ContextTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test()
    {
        /** @var Context $context */
        $context = (new Context(
            \Mockery::mock(Strategy::class)
                ->shouldReceive('__invoke')
                ->with($data = [], $context = 'foo')
                ->once()
                ->getMock(),
            $context
        ))->setMapper(new Mapper);

        $context($data);
    }
}
