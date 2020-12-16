<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\Strategy\Unique;
use ScriptFUSIONTest\MockFactory;

final class UniqueTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test()
    {
        /** @var Unique $unique */
        $unique = (new Unique(null))->setMapper(MockFactory::mockMapper(['foo' => 'bar', 'baz' => 'bar']));

        self::assertSame(['foo' => 'bar'], $unique([]));
    }
}
