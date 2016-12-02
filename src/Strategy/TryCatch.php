<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

/**
 * TryCatch uses the handler callback to catch and manage any exception thrown by
 * the primary strategy, if an exception was raised delegates to a fallback expression.
 */
class TryCatch extends Decorator
{
    private $expression;
    private $handler;

    /**
     * @param Strategy $strategy
     * @param callable $handler
     * @param Strategy|Mapping|array|mixed $expression
     */
    public function __construct(Strategy $strategy, callable $handler, $expression)
    {
        parent::__construct($strategy);
        $this->handler = $handler;
        $this->expression = $expression;
    }

    public function __invoke($data, $context = null)
    {
        try {
            return parent::__invoke($data, $context);
        } catch (\Exception $e) {
            call_user_func($this->handler, $e);

            return $this->delegate($this->expression, $data, $context);
        }
    }
}
