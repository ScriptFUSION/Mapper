<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

/**
 * Decorates a data collection by applying a transformation to each datum.
 */
class Collection extends Delegate
{
    private $transformation;

    /**
     * Initializes this instance with the specified strategy or mapping that
     * yields a data collection and the specified strategy or mapping that
     * describes how to transform each datum in the collection.
     *
     * @param Strategy|Mapping|array|mixed $collection Data collection.
     * @param Strategy|Mapping|array|mixed $transformation Transformation
     *     strategy or mapping fragment.
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

        return array_map(function ($context) use ($data) {
            return $this->delegate($this->transformation, $data, $context);
        }, $collection);
    }
}
