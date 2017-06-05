<?php
namespace ScriptFUSION\Mapper;

/**
 * Maps collections of records.
 */
class CollectionMapper extends Mapper
{
    /**
     * @param \Iterator $collection
     * @param Mapping|null $mapping
     * @param mixed $context
     *
     * @return \Generator
     *
     * @throws InvalidRecordException A record in the specified collection was not an array type.
     */
    public function mapCollection(\Iterator $collection, Mapping $mapping = null, $context = null)
    {
        foreach ($collection as $key => $record) {
            if (!is_array($record)) {
                throw new InvalidRecordException('Record must be an array.');
            }

            yield $key => $mapping
                ? $this->mapMapping($record, $mapping, $context, $key)
                : $record;
        }
    }
}
