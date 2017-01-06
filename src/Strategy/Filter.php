<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

/**
 * Filters null values or values rejected by the specified callback
 */
class Filter extends Delegate
{
    private $callback;

    /**
     * @param Strategy|Mapping|array|mixed $expression Expression.
     * @param callable|null $callback Callback function that receives the current value as its first argument.
     */
    public function __construct($expression, callable $callback = null)
    {
        parent::__construct($expression);

        $this->callback = $callback;
    }

    public function __invoke($data, $context = null)
    {
        if (!is_array($data = parent::__invoke($data, $context))) {
            return null;
        }

        $filter = function (array $data, $context) {
            $callback = $this->callback ?: function ($value) {
                return $value !== null;
            };

            foreach ($data as $datum) {
                if ($callback($datum, $context)) {
                    yield $datum;
                }
            }
        };

        return iterator_to_array($filter($data, $context));
    }
}
