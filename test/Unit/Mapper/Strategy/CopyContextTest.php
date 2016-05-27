<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use ScriptFUSION\Mapper\Strategy\CopyContext;

final class CopyContextTest extends \PHPUnit_Framework_TestCase
{
    public function testNoPath()
    {
        $copyContext = new CopyContext;

        self::assertSame('foo', $copyContext(null, 'foo'));
    }
}
