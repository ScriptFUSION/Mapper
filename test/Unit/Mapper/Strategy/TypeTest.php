<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\DataType;
use ScriptFUSION\Mapper\Strategy\Strategy;
use ScriptFUSION\Mapper\Strategy\Type;
use ScriptFUSIONTest\MockFactory;

final class TypeTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test()
    {
        $type = new Type(DataType::Integer, \Mockery::mock(Strategy::class));
        $type->setMapper(MockFactory::mockMapper('123'));

        self::assertSame(123, $type([]));
    }
}
