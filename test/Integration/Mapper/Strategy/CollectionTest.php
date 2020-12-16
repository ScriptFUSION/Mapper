<?php
namespace ScriptFUSIONTest\Integration\Mapper\Strategy;

use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Collection;
use ScriptFUSION\Mapper\Strategy\CopyContext;
use ScriptFUSIONTest\MockFactory;

final class CollectionTest extends TestCase
{
    public function testNull()
    {
        /** @var Collection $collection */
        $collection = (new Collection(null, null))->setMapper(MockFactory::mockMapper(null));

        self::assertNull($collection([]));
    }

    public function testCollection()
    {
        /** @var Collection $collection */
        $collection = (new Collection(
            $this->createArray('foo'),
            [
                'bar' => new CopyContext('foo'),
            ]
        ))->setMapper(new Mapper);

        self::assertSame($this->createArray('bar'), $collection([]));
    }

    private function createArray($key)
    {
        return array_combine(
            // Keys must not change.
            range('a', 'j'),
            array_map(
                function ($number) use ($key) {
                    return [$key => $number];
                },
                range('k', 't')
            )
        );
    }
}
