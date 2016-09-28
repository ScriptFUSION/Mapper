<?php
namespace ScriptFUSION\Mapper;

use ScriptFUSION\Mapper\Strategy\Strategy;

/**
 * Represents a mapping of keys and mappable values.
 */
abstract class Mapping
{
    /**
     * @var array
     */
    private $mapping;

    private $wrapped = false;

    /**
     * Initializes this mapping.
     */
    public function __construct()
    {
        /* Array-based mapping. */
        if (is_array($mapping = $this->createMapping())) {
            $this->mapping = $mapping;
        } /* Strategy-based mapping. */
        elseif ($mapping instanceof Strategy) {
            $this->mapping = [$mapping];
            $this->wrapped = true;
        } else {
            throw new InvalidMappingException('Invalid mapping: must be array or an instance of Strategy.');
        }
    }

    /**
     * Creates a mapping of key names and expressions or a straegy.
     *
     * @return array|Strategy Mapping.
     */
    abstract protected function createMapping();

    /**
     * Converts the mapping to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->mapping;
    }

    /**
     * Gets a value indicating whether the mapping has been wrapped in an array.
     *
     * @return boolean True if the mapping is wrapped in an outer array, otherwise false.
     */
    public function isWrapped()
    {
        return $this->wrapped;
    }
}
