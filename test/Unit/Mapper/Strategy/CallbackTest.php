<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\Strategy\Callback;

final class CallbackTest extends TestCase
{
    public function test()
    {
        $callback = new Callback(function ($a, $b) {
            return "$a$b";
        });

        self::assertSame('foobar', $callback('foo', 'bar'));
    }
}
