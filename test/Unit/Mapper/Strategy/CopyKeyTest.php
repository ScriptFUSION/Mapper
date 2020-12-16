<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\Strategy\CopyKey;

final class CopyKeyTest extends TestCase
{
    public function testDefault()
    {
        $copyKey = new CopyKey;
        self::assertNull($copyKey([]));
    }

    public function testRoundTrip()
    {
        $copyKey = new CopyKey;
        $copyKey->setKey($key = 'foo');

        self::assertSame($key, $copyKey([]));
    }
}
