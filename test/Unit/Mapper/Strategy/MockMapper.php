<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapper;

trait MockMapper
{
    /**
     * @param mixed $data
     *
     * @return Mapper
     */
    private function mockMapper($data)
    {
        return \Mockery::mock(Mapper::class)->shouldReceive('map')->andReturn($data)->getMock();
    }
}
