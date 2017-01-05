<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

/**
 * Maps a collection of data by applying a transformation to each datum using a callback.
 */
class Collection extends Delegate
{
    private $transformation;

    /**
     * Initializes this instance with the specified strategy or mapping that yields a data collection and the specified
     * strategy or mapping that describes how to transform each datum in the collection.
     *
     * @param Strategy|Mapping|array|mixed $collection Data collection expression that maps to an array
     * @param Strategy|Mapping|array|mixed $transformation Transformation expression.
     *     The current datum is passed as context.
     */
    public function __construct($collection, $transformation)
    {
        parent::__construct($collection);

        $this->transformation = $transformation;
    }

    public function __invoke($data, $context = null)
    {
        if (!is_array($collection = parent::__invoke($data, $context))) {
            return null;
        }

        array_walk($collection, function (&$value, $key) use ($data) {
            $value = $this->delegate($this->transformation, $data, $value, $key);
        });

        return $collection;
    }
}
