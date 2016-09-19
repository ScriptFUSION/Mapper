<?php
namespace ScriptFUSION\Mapper;

/**
 * Represents a mapping of keys and mappable values.
 */
abstract class Mapping extends \ArrayObject
{
    /**
     * Initializes this mapping.
     */
    public function __construct()
    {
        parent::__construct($this->createMapping());
    }

    /**
     * Creates a mapping of key names and mappable values.
     *
     * @return array Mapping.
     */
    abstract protected function createMapping();
}
