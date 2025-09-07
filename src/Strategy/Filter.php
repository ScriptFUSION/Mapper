<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

/**
 * Filters null values or values rejected by the specified callback.
 */
class Filter extends Delegate
{
    /**
     * @var callable|null
     */
    private $callback;

    /**
     * @param Strategy|Mapping|array|mixed $expression Expression.
     * @param callable|null $callback Callback function that receives the current value as its first argument, the
     *     current key as its second argument and context as its third argument.
     */
    public function __construct($expression, ?callable $callback = null)
    {
        parent::__construct($expression);

        $this->callback = $callback;
    }

    public function __invoke($data, $context = null)
    {
        if (!is_array($data = parent::__invoke($data, $context))) {
            return null;
        }

        return iterator_to_array(self::filter($this->callback ?: [$this, 'filterNulls'], $data, $context));
    }

    private static function filter(callable $callback, array $data, $context)
    {
        foreach ($data as $key => $datum) {
            if ($callback($datum, $key, $context)) {
                yield $key => $datum;
            }
        }
    }

    private static function filterNulls($value)
    {
        return $value !== null;
    }
}
