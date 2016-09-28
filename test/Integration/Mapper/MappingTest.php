<?php
namespace ScriptFUSIONTest\Integration\Mapper;

use ScriptFUSION\Mapper\AnonymousMapping;
use ScriptFUSION\Mapper\InvalidMappingException;
use ScriptFUSION\Mapper\Mapping;
use ScriptFUSION\Mapper\Strategy\Strategy;

final class MappingTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayBasedMapping()
    {
        $mapping = new AnonymousMapping($fragment = ['foo' => 'foo']);

        self::assertSame($fragment, $mapping->toArray());
        self::assertFalse($mapping->isWrapped());
    }

    public function testStrategyBasedMapping()
    {
        $mapping = new AnonymousMapping($strategy = \Mockery::mock(Strategy::class));

        self::assertSame([$strategy], $mapping->toArray());
        self::assertTrue($mapping->isWrapped());
    }

    public function testInvalidMapping()
    {
        $this->setExpectedException(InvalidMappingException::class);

        new AnonymousMapping(\Mockery::mock(Mapping::class));
    }
}
