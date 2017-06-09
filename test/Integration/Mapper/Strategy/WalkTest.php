<?php
namespace ScriptFUSIONTest\Integration\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Walk;

final class WalkTest extends \PHPUnit_Framework_TestCase
{
    public function testWalk()
    {
        /** @var Walk $walk */
        $walk = (new Walk(['foo' => ['bar' => 'baz']], 'foo->bar'))
            ->setMapper(new Mapper)
        ;

        self::assertSame('baz', $walk([]));
    }
}
