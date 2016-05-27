<?php
namespace ScriptFUSIONTest\Functional\Mapper;

use ScriptFUSION\Mapper\AnonymousMapping;
use ScriptFUSION\Mapper\Mapper;

final class MapperTest extends \PHPUnit_Framework_TestCase
{
    /** @var Mapper */
    private $mapper;

    protected function setUp()
    {
        $this->mapper = new Mapper;
    }

    /**
     * Tests that when there is nothing to mapMapping an invalid Generator is returned.
     */
    public function testMapNothing()
    {
        $mapped = $this->mapper->mapRecords(new \EmptyIterator);

        self::assertFalse($mapped->valid());
    }

    /**
     * Tests that when no mapping is specified records are unaltered.
     */
    public function testNoMapping()
    {
        $mapped = $this->mapper->mapRecords(new \ArrayIterator($records = [['foo']]));

        self::assertSame($records, iterator_to_array($mapped));
    }

    /**
     * Tests that when mapping is empty records are mapped to empty records.
     */
    public function testEmptyMapping()
    {
        $mapped = $this->mapper->mapRecords(new \ArrayIterator([['foo']]), new AnonymousMapping($record = []));

        self::assertSame([$record], iterator_to_array($mapped));
    }

    /**
     * Tests that multiple records are mapped similarly.
     */
    public function testMapRecords()
    {
        $mapped = $this->mapper->mapRecords(new \ArrayIterator([[1], [2]]), new AnonymousMapping($record = ['foo']));

        self::assertSame([$record, $record], iterator_to_array($mapped));
    }
}
