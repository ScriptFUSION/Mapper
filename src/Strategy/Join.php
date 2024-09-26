<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

/**
 * Joins sub-string expressions together with a glue string.
 */
class Join extends Delegate
{
    private $glue;

    /**
     * Initializes this instance with the specified glue to join the specified expressions together.
     *
     * @param string $glue Glue.
     * @param Strategy|Mapping|string[]|string ...$expressions Expressions to join or a single expression that resolves
     *     to an array to join.
     */
    public function __construct($glue, ...$expressions)
    {
        parent::__construct($expressions);

        $this->glue = "$glue";
    }

    public function __invoke($data, $context = null)
    {
        $pieces = parent::__invoke($data, $context);

        if (count($pieces) === 1 && is_array($pieces[0])) {
            // Unwrap.
            $pieces = $pieces[0];
        }

        return implode($this->glue, $pieces);
    }
}
