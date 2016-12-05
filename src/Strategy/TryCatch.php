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
     * @param Strategy|Mapping|array|mixed $expression
     * @param callable|null $handler
     */
    public function __construct(Strategy $strategy, $expression, callable $handler = null)
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
            if ($this->handler !== null) {
                call_user_func($this->handler, $e);
            }

            return $this->delegate($this->expression, $data, $context);
        }
    }
}
