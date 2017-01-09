<?php
namespace ScriptFUSIONTest\Functional;

use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Collection;
use ScriptFUSION\Mapper\Strategy\Context;
use ScriptFUSION\Mapper\Strategy\Copy;
use ScriptFUSION\Mapper\Strategy\CopyContext;
use ScriptFUSION\Mapper\Strategy\CopyKey;

/**
 * Tests that the key context is correctly propagated through different expression types.
 */
final class KeyPropagationTest extends \PHPUnit_Framework_TestCase
{
    public function testFragmentPropagation()
    {
        $mapped = (new Mapper)->map(
            [
                'foo' => [
                    'bar' => [],
                ],
            ],
            new Collection(
                new Copy('foo'),
                ['foo' => new CopyKey]
            )
        );

        self::assertSame(['bar' => ['foo' => 'bar']], $mapped);
    }

    public function testStrategyPropagation()
    {
        $mapped = (new Mapper)->map(
            [
                'foo' => [
                    'bar' => [],
                ],
            ],
            new Collection(
                new Copy('foo'),
                new Context(
                    new CopyContext,
                    new CopyKey
                )
            )
        );

        self::assertSame(['bar' => 'bar'], $mapped);
    }
}
