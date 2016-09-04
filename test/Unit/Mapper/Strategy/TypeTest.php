<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use ScriptFUSION\DataType;
use ScriptFUSION\Mapper\Strategy\Strategy;
use ScriptFUSION\Mapper\Strategy\Type;
use ScriptFUSIONTest\MockFactory;

final class TypeTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration;

    public function test()
    {
        $type = new Type(DataType::INTEGER(), \Mockery::mock(Strategy::class));
        $type->setMapper(MockFactory::mockMapper('123'));

        self::assertSame(123, $type([]));
    }
}
