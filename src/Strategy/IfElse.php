<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

/**
 * Delegates to one expression or another depending on whether the specified condition loosely evaluates to true.
 */
class IfElse extends Delegate
{
    /** @var Strategy|Mapping|array|mixed */
    private $if;

    /** @var Strategy|Mapping|array|mixed */
    private $else;

    /**
     * Initializes this instance with the specified condition, the specified
     * strategy or mapping to be resolved when condition is non-null and,
     * optionally, the specified strategy or mapping to be resolved when
     * condition is null.
     *
     * @param Strategy|Mapping|array|mixed $condition Condition.
     * @param Strategy|Mapping|array|mixed $if Primary expression.
     * @param Strategy|Mapping|array|mixed|null $else Optional. Fallback expression.
     */
    public function __construct($condition, $if, $else = null)
    {
        parent::__construct($condition);

        $this->if = $if;
        $this->else = $else;
    }

    /**
     * Resolves the stored strategy or mapping when the stored condition
     * resolves to a non-null value, otherwise returns the stored default
     * value.
     *
     * @param mixed $data
     * @param mixed $context
     *
     * @return mixed
     */
    public function __invoke($data, $context = null)
    {
        if (parent::__invoke($data, $context)) {
            return $this->delegate($this->if, $data, $context);
        }

        if ($this->else !== null) {
            return $this->delegate($this->else, $data, $context);
        }
    }
}
