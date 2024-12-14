<?php
declare(strict_types=1);

namespace ScriptFUSIONTest\Integration\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Trim;
use PHPUnit\Framework\TestCase;

/**
 * @see Trim
 */
final class TrimTest extends TestCase
{
    public function test(): void
    {
        $trim = (new Trim(' foo '))->setMapper(new Mapper());

        self::assertSame('foo', $trim([]));
    }

    /**
     * Tests that trimming null returns the empty string.
     */
    public function testNull(): void
    {
        $trim = (new Trim(null))->setMapper(new Mapper());

        self::assertSame('', $trim([]));
    }
}
