<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use ScriptFUSION\Mapper\Strategy\Callback;

final class CallbackTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $callback = new Callback(function ($a, $b) {
            return "$a$b";
        });

        self::assertSame('foobar', $callback('foo', 'bar'));
    }
}
