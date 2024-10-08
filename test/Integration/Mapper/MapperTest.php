<?php
namespace ScriptFUSIONTest\Integration\Mapper;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\AnonymousMapping;
use ScriptFUSION\Mapper\InvalidExpressionException;
use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\MapperAware;
use ScriptFUSION\Mapper\Strategy\Copy;
use ScriptFUSION\Mapper\Strategy\Strategy;

final class MapperTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var Mapper */
    private $mapper;

    protected function setUp(): void
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

    /**
     * @dataProvider provideScalars
     */
    public function testMapScalars($scalar)
    {
        $mapped = $this->mapper->map([], $scalar);

        self::assertSame($scalar, $mapped);
    }

    public static function provideScalars()
    {
        return [
            'string empty' => [''],
            'string non-empty' => ['foo'],
            'integer zero' => [0],
            'integer non-zero' => [-1],
            'float zero' => [0.],
            'float non-zero' => [-1.],
            'bool true' => [true],
            'bool false' => [false],
        ];
    }

    public function testMapNull()
    {
        $mapped = $this->mapper->map([], $output = ['foo' => null]);

        self::assertSame($output, $mapped);
    }

    public function testMapInvalidObject()
    {
        $this->expectException(InvalidExpressionException::class);

        $this->mapper->map([], (object)[]);
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
