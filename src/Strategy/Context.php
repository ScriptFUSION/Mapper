<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

/**
 * Replaces the context for the specified expression.
 */
class Context extends Delegate
{
    private $expression;

    /**
     * @param Strategy|Mapping|array|mixed $expression Expression.
     * @param Strategy|Mapping|array|mixed $context New context.
     */
    public function __construct($expression, $context)
    {
        parent::__construct($expression);

        $this->expression = $context;
    }

    public function __invoke($data, $context = null)
    {
        return parent::__invoke($data, $this->delegate($this->expression, $data, $context));
    }
}
