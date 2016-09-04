<?php
namespace ScriptFUSIONTest\Integration\Mapper;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use ScriptFUSION\Mapper\AnonymousMapping;
use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\MapperAware;
use ScriptFUSION\Mapper\Strategy\Copy;
use ScriptFUSION\Mapper\Strategy\Strategy;

final class MapperTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration;

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

    public function testInjectDependencies()
    {
        $this->mapper->map(
            ['foo'],
            \Mockery::spy(implode(',', [Strategy::class, MapperAware::class]))
                ->shouldReceive('setMapper')
                ->with($this->mapper)
                ->once()
                ->getMock()
        );
    }
}
