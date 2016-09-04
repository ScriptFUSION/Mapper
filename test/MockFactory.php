<?php
namespace ScriptFUSIONTest;

use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\StaticClass;

final class MockFactory
{
    use StaticClass;

    /**
     * @param mixed $data
     *
     * @return Mapper
     */
    public static function mockMapper($data)
    {
        return \Mockery::mock(Mapper::class)->shouldReceive('map')->andReturn($data)->getMock();
    }
}
