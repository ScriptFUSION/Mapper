<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

/**
 * Walks a nested structure to the specified element in the same manner as Copy.
 */
class Walk extends Delegate
{
    private $path;

    /**
     * @param Strategy|Mapping|array|mixed $expression Expression to walk.
     * @param Strategy|Mapping|array|mixed $path Array of path components, string of `->`-delimited path components or
     *     a strategy or mapping resolving to such an expression.
     */
    public function __construct($expression, $path)
    {
        parent::__construct($expression);

        $this->path = $path;
    }

    public function __invoke($data, $context = null)
    {
        $copy = (new Copy($this->delegate($this->path, $data, $context)))->setMapper($this->getMapper());

        return $copy(parent::__invoke($data, $context), $context);
    }
}
