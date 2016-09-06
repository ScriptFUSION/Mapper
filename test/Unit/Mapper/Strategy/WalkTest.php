<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use ScriptFUSION\Mapper\Strategy\Walk;
use ScriptFUSIONTest\MockFactory;

final class WalkTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        /** @var Walk $walk */
        $walk = (new Walk(null, 'foo->bar'))
            ->setMapper(MockFactory::mockMapper(['foo' => ['bar' => 'baz']]))
        ;

        self::assertSame('baz', $walk([]));
    }
}
