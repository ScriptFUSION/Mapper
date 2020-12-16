<?php
namespace ScriptFUSIONTest\Unit\Mapper;

use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\Mapper;
use ScriptFUSIONTest\MapperAwareStub;

final class MapperAwareTraitTest extends TestCase
{
    public function testGetMapper()
    {
        $mapperAware = (new MapperAwareStub)->setMapper($mapper = \Mockery::mock(Mapper::class));

        self::assertSame($mapper, $mapperAware->getMapperPublic());
    }
}
