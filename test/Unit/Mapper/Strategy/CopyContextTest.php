<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\Strategy\CopyContext;
use ScriptFUSIONTest\MockFactory;

final class CopyContextTest extends TestCase
{
    public function testNoPath()
    {
        $copyContext = self::createStrategy(null);

        self::assertSame('foo', $copyContext(null, 'foo'));
    }

    public function testPath()
    {
        $copyContext = self::createStrategy('foo->bar');

        self::assertSame('baz', $copyContext(null, ['foo' => ['bar' => 'baz']]));
    }

    private static function createStrategy($path)
    {
        return (new CopyContext($path))->setMapper(MockFactory::mockMapper($path));
    }
}
