<?php
namespace ScriptFUSIONTest\Integration\Mapper;

use ScriptFUSION\Mapper\AnonymousMapping;
use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Copy;

final class MapperTest extends \PHPUnit_Framework_TestCase
{
    /** @var Mapper */
    private $mapper;

    protected function setUp()
    {
        $this->mapper = new Mapper;
    }

    public function testMapStrategy()
    {
        $mapped = $this->mapper->map(['foo'], new Copy(0));

        self::assertSame('foo', $mapped);
    }

    public function testMapMapping()
    {
        $mapped = $this->mapper->map(['foo'], new AnonymousMapping(['bar' => new Copy(0)]));

        self::assertSame(['bar' => 'foo'], $mapped);
    }

    public function testMapFragment()
    {
        $mapped = $this->mapper->map(['foo'], ['bar' => new Copy(0)]);

        self::assertSame(['bar' => 'foo'], $mapped);
    }
}
