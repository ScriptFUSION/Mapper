<?php
namespace ScriptFUSION\Mapper\Strategy;

/**
 * Joins sub-string expressions together with a glue string.
 */
class Join extends Delegate
{
    private $glue;

    /**
     * Initializes this instance with the specified glue to join the specified sub-strings together.
     *
     * @param string $glue Glue.
     * @param array ...$expressions Sub-string expressions.
     */
    public function __construct($glue, ...$expressions)
    {
        parent::__construct($expressions);

        $this->glue = "$glue";
    }

    public function __invoke($data, $context = null)
    {
        return implode($this->glue, parent::__invoke($data, $context));
    }
}
