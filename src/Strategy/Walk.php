<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\MapperAware;
use ScriptFUSION\Mapper\MapperAwareTrait;
use ScriptFUSION\Mapper\Mapping;

/**
 * Walks a nested structure to the specified element in the same manner as Copy.
 */
class Walk extends Copy implements MapperAware
{
    use MapperAwareTrait;

    private $expression;

    /**
     * @param Strategy|Mapping|array|mixed $expression Expression.
     * @param array|string $path Array of path components or string of `->`-delimited components.
     */
    public function __construct($expression, $path)
    {
        parent::__construct($path);

        $this->expression = $expression;
    }

    public function __invoke($data, $context = null)
    {
        return parent::__invoke($this->getMapper()->map($data, $this->expression, $context), $context);
    }
}
