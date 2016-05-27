<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use ScriptFUSION\DataType;
use ScriptFUSION\Mapper\Strategy\Strategy;
use ScriptFUSION\Mapper\Strategy\Type;

final class DataTypeTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration, MockMapper;

    public function test()
    {
        $type = new Type(DataType::INTEGER(), \Mockery::mock(Strategy::class));
        $type->setMapper($this->mockMapper('123'));

        self::assertSame(123, $type([]));
    }
}
