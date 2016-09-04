<?php
namespace ScriptFUSIONTest\Unit\Mapper;

use ScriptFUSION\Mapper\Mapper;
use ScriptFUSIONTest\MapperAwareStub;

final class MapperAwareTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMapper()
    {
        $mapperAware = (new MapperAwareStub)->setMapper($mapper = \Mockery::mock(Mapper::class));

        self::assertSame($mapper, $mapperAware->getMapperPublic());
    }
}
