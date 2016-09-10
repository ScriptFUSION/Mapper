<?php
namespace ScriptFUSIONTest\Integration\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Collection;
use ScriptFUSION\Mapper\Strategy\CopyContext;
use ScriptFUSIONTest\MockFactory;

final class CollectionTest extends \PHPUnit_Framework_TestCase
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
            $this->createCollection('foo'),
            [
                'bar' => new CopyContext('foo'),
            ]
        ))->setMapper(new Mapper);

        self::assertSame($this->createCollection('bar'), $collection([]));
    }

    private function createCollection($key)
    {
        return array_map(
            function ($number) use ($key) {
                return [$key => $number];
            },
            range(1, 10)
        );
    }
}
