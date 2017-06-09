<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapper;
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
     * @param Strategy|Mapping|array|mixed $expression Expression to walk.
     * @param Strategy|Mapping|array|mixed $path Array of path components, string of `->`-delimited path components or
     *     a strategy or mapping resolving to such an expression.
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

    public function setMapper(Mapper $mapper)
    {
        $this->copy->setMapper($mapper);

        return parent::setMapper($mapper);
    }
}
