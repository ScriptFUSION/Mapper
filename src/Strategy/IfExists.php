<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

/**
 * Decorates a strategy or mapping when a condition is non-null.
 */
class IfExists extends Decorator
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
     * @param Strategy $condition Condition.
     * @param Strategy|Mapping|array|mixed $if Strategy or mapping.
     * @param Strategy|Mapping|array|mixed $else Optional. Strategy or mapping.
     */
    public function __construct(Strategy $condition, $if, $else = null)
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
        if (parent::__invoke($data, $context) !== null) {
            return $this->delegate($this->if, $data, $context);
        }

        if ($this->else !== null) {
            return $this->delegate($this->else, $data, $context);
        }
    }
}