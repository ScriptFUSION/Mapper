<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use ScriptFUSION\Mapper\Strategy\CopyKey;

final class CopyKeyTest extends \PHPUnit_Framework_TestCase
{
    public function testRoundTrip()
    {
        $copyKey = new CopyKey;
        $copyKey->setKey($key = 'foo');

        self::assertSame($key, $copyKey([]));
    }
}
