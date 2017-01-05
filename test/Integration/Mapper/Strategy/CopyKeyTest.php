<?php
namespace ScriptFUSIONTest\Integration\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Collection;
use ScriptFUSION\Mapper\Strategy\Copy;
use ScriptFUSION\Mapper\Strategy\CopyKey;

final class CopyKeyTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $mapped = (new Mapper)->map(
            [
                'foo' => 'bar',
                'baz' => [
                    'qux' => 'quux',
                    'corge' => 123,
                ],
            ],
            [
                'foo' => new CopyKey,
                'bar' => new Collection(
                    new Copy('baz'),
                    new CopyKey
                ),
            ]
        );

        self::assertSame([
            'foo' => null,
            'bar' => [
                'qux' => 'qux',
                'corge' => 'corge',
            ],
        ], $mapped);
    }
}
