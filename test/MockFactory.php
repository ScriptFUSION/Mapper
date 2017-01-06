<?php
namespace ScriptFUSIONTest;

use Mockery\MockInterface;
use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\StaticClass;

final class MockFactory
{
    use StaticClass;

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
