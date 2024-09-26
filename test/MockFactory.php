<?php
namespace ScriptFUSIONTest;

use Mockery\MockInterface;
use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\StaticClass;

final class MockFactory
{
    use StaticClass;

    public static function mockMapper(mixed $data): Mapper&MockInterface
    {
        return \Mockery::mock(Mapper::class)->shouldReceive('map')->andReturn($data)->getMock();
    }

    public static function mockMapperEcho(): Mapper&MockInterface
    {
        return \Mockery::mock(Mapper::class)->expects('map')->andReturnArg(1)->getMock();
    }
}
