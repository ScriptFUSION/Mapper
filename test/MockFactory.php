<?php
namespace ScriptFUSIONTest;

use Mockery\MockInterface;
use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Strategy;
use ScriptFUSION\StaticClass;

final class MockFactory
{
    use StaticClass;

    /**
     * @param mixed $data
     *
     * @return Strategy|MockInterface
     */
    public static function mockStrategy($data)
    {
        return \Mockery::mock(Strategy::class)->shouldReceive('__invoke')->andReturn($data)->getMock();
    }

    /**
     * @param mixed $data
     *
     * @return Mapper|MockInterface
     */
    public static function mockMapper($data)
    {
        return \Mockery::mock(Mapper::class)->shouldReceive('map')->andReturn($data)->getMock();
    }
}
