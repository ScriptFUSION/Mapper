<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

/**
 * Delegates to one expression or another depending on whether the specified condition maps to null.
 */
class IfExists extends IfElse
{
    /**
     * Initializes this instance with the specified condition, the specified
     * strategy or mapping to be resolved when condition is non-null and,
     * optionally, the specified strategy or mapping to be resolved when
     * condition is null.
     *
     * @param Strategy $condition Condition.
     * @param Strategy|Mapping|array|mixed $if Primary expression.
     * @param Strategy|Mapping|array|mixed|null $else Optional. Fallback expression.
     */
    public function __construct(Strategy $condition, $if, $else = null)
    {
        parent::__construct(function ($data, $context = null) use ($condition) {
            return $this->delegate($condition, $data, $context) !== null;
        }, $if, $else);
    }
}
