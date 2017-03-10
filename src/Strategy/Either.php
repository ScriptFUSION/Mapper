<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

/**
 * Either uses the primary strategy, if it returns non-null, otherwise delegates to a fallback expression.
 */
class Either extends IfElse
{
    /**
     * @param Strategy $strategy
     * @param Strategy|Mapping|array|mixed $expression
     */
    public function __construct(Strategy $strategy, $expression)
    {
        parent::__construct(new Exists($strategy), $strategy, $expression);
    }
}
