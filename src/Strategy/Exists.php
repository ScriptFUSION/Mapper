<?php
namespace ScriptFUSION\Mapper\Strategy;

/**
 * Returns true or false if the resolved value of the strategy or the path exists.
 */
class Exists extends Delegate
{
    /**
     * Initializes this instance with the specified strategy or path.
     *
     * @param Strategy|array|string $strategyOrPath Strategy, array of path components or string of `->`-delimited components.
     */
    public function __construct($strategyOrPath)
    {
        parent::__construct($strategyOrPath instanceof Strategy ? $strategyOrPath : new Copy($strategyOrPath));
    }

    /**
     * Return true when the strategy resolves to a non-null value, otherwise false.
     *
     * @param mixed $data
     * @param mixed $context
     *
     * @return mixed
     */
    public function __invoke($data, $context = null)
    {
        return parent::__invoke($data, $context) !== null;
    }
}
