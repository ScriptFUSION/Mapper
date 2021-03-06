<?php
namespace ScriptFUSIONTest\Integration\Mapper;

use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\AnonymousMapping;
use ScriptFUSION\Mapper\CollectionMapper;
use ScriptFUSION\Mapper\InvalidRecordException;

final class CollectionMapperTest extends TestCase
{
    /** @var CollectionMapper */
    private $mapper;

    protected function setUp(): void
    {
        $this->mapper = new CollectionMapper;
    }

    /**
     * Tests that when an invalid collection is specified an exception is thrown.
     */
    public function testMapInvalidCollection()
    {
        $this->expectException(InvalidRecordException::class);

        $this->mapper->mapCollection(new \ArrayIterator(['foo']))->valid();
    }

    /**
     * Tests that when there is nothing to map an invalid Generator is returned.
     */
    public function testMapNothing()
    {
        $mapped = $this->mapper->mapCollection(new \EmptyIterator);

        self::assertFalse($mapped->valid());
    }

    /**
     * Tests that when no mapping is specified records are unaltered.
     */
    public function testNoMapping()
    {
        $mapped = $this->mapper->mapCollection(new \ArrayIterator($records = [['foo']]));

        self::assertSame($records, iterator_to_array($mapped));
    }

    /**
     * Tests that when mapping is empty records are mapped to empty records.
     */
    public function testEmptyMapping()
    {
        $mapped = $this->mapper->mapCollection(new \ArrayIterator([['foo']]), new AnonymousMapping($record = []));

        self::assertSame([$record], iterator_to_array($mapped));
    }

    /**
     * Tests that multiple records are mapped similarly.
     */
    public function testMapCollection()
    {
        $mapped = $this->mapper->mapCollection(new \ArrayIterator([[1], [2]]), new AnonymousMapping($record = ['foo']));

        self::assertSame([$record, $record], iterator_to_array($mapped));
    }

    /**
     * Tests that the keys of a mapped collection are preserved.
     */
    public function testCollectionKeysPreserved()
    {
        $mapped = $this->mapper->mapCollection(
            new \ArrayIterator($input = ['foo' => ['bar']]),
            new AnonymousMapping(['baz'])
        );

        self::assertSame(['foo' => ['baz']], iterator_to_array($mapped));
    }
}
