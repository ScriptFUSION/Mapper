<?php
namespace ScriptFUSION\Mapper;

use ScriptFUSION\Mapper\Strategy\Strategy;

/**
 * Maps collections of records.
 */
class CollectionMapper extends Mapper
{
    /**
     * @param \Iterator $collection
     * @param Strategy|Mapping|array|mixed $expression Expression.
     * @param mixed $context
     *
     * @return \Generator
     *
     * @throws InvalidRecordException A record in the specified collection was not an array type.
     */
    public function mapCollection(\Iterator $collection, $expression = null, $context = null)
    {
        foreach ($collection as $key => $record) {
            if (!is_array($record)) {
                throw new InvalidRecordException('Record must be an array.');
            }

            yield $key => $expression !== null
                ? $this->mapMapping($record, $expression, $context, $key)
                : $record;
        }
    }
}
