<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

/**
 * Either uses the primary strategy, if it returns non-null, otherwise delegates to a fallback expression.
 */
class Either extends Decorator
{
    private $expression;

    /**
     * @param Strategy $strategy
     * @param Strategy|Mapping|array|mixed $expression
     */
    public function __construct(Strategy $strategy, $expression)
    {
        parent::__construct($strategy);

        $this->expression = $expression;
    }

    public function __invoke($data, $context = null)
    {
        if (($result = parent::__invoke($data, $context)) !== null) {
            return $result;
        }

        return $this->delegate($this->expression, $data, $context);
    }
}
