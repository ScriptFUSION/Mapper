<?php
namespace ScriptFUSIONTest\Integration\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Translate;
use ScriptFUSIONTest\MockFactory;

final class TranslateTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration;

    public function testValueFound()
    {
        $translate = (new Translate(MockFactory::mockStrategy('foo'), ['foo' => 'bar']))->setMapper(new Mapper);

        self::assertSame('bar', $translate([]));
    }

    public function testValueNotFound()
    {
        $translate = (new Translate(MockFactory::mockStrategy('foo'), ['bar' => 'bar']))->setMapper(new Mapper);

        self::assertNull($translate([]));
    }
}
