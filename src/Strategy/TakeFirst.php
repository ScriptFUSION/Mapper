<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

/**
 * Takes the first value from a collection one or more times according to the specified depth. If the depth exceeds the
 * number of nesting levels of the collection the last item encountered will be returned.
 */
class TakeFirst extends Delegate
{
    private $depth;

    /**
     * @param Strategy|Mapping|array|mixed $collection Expression that maps to an array.
     * @param int $depth Number of times to descending into nested collections.
     */
    public function __construct($collection, $depth = 1)
    {
        parent::__construct($collection);

        $this->depth = max(1, $depth|0);
    }

    public function __invoke($data, $context = null)
    {
        $depth = $this->depth;
        $structure = parent::__invoke($data, $context);

        while (is_array($structure) && $depth--) {
            $structure = reset($structure);
        }

        return $structure;
    }
}
