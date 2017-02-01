<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

/**
 * Walks a nested structure to the specified element in the same manner as Copy.
 */
class Walk extends Delegate
{
    /**
     * @var Copy
     */
    private $copy;

    /**
     * @param Strategy|Mapping|array|mixed $expression Expression.
     * @param array|string $path Array of path components or string of `->`-delimited components.
     */
    public function __construct($expression, $path)
    {
        parent::__construct($expression);

        $this->copy = new Copy($path);
    }

    public function __invoke($data, $context = null)
    {
        return call_user_func($this->copy, parent::__invoke($data, $context), $context);
    }
}
