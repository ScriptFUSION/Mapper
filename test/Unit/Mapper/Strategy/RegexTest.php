<?php
declare(strict_types=1);

namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\Strategy\Regex;
use ScriptFUSIONTest\MockFactory;

final class RegexTest extends TestCase
{
    public function testRegexMatch(): void
    {
        $regex = (new Regex('Alfa Beta Charlie', '[\h(.+)\h]', 1))->setMapper(MockFactory::mockMapperEcho());

        self::assertSame('Beta', $regex([]));
    }

    public function testRegexNonMatch(): void
    {
        $regex = (new Regex('Alfa', '[Beta]'))->setMapper(MockFactory::mockMapperEcho());

        self::assertNull($regex([]));
    }

    public function testRegexMatchArray(): void
    {
        $regex = (new Regex('Alfa Beta Charlie', '[(A\w+).+?(C\w+)]', [1, 2]))->setMapper(MockFactory::mockMapperEcho());

        self::assertSame(['Alfa', 'Charlie'], $regex([]));
    }
}
