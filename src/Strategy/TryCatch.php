<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

/**
 * Tries the primary strategy and falls back to an expression if an exception is thrown.
 */
class TryCatch extends Decorator
{
    /** @var callable */
    private $handler;

    /** @var Strategy|Mapping|array|mixed */
    private $expression;

    /**
     * @param Strategy $strategy Primary strategy.
     * @param callable $handler Exception handler.
     * @param Strategy|Mapping|array|mixed $expression Fallback expression.
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
        } catch (\Exception $exception) {
            call_user_func($this->handler, $exception, $data);

            return $this->delegate($this->expression, $data, $context);
        }
    }
}
