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
     * @throws \Exception
     */
    public function mapCollection(\Iterator $collection, Mapping $mapping = null, $context = null)
    {
        foreach ($collection as $record) {
            if (!is_array($record)) {
                throw new \Exception('Record must be an array.'); // TODO: Specific exception type.
            }

            yield $mapping
                ? $this->mapMapping($record, $mapping, $context)
                : $record;
        }
    }
}
