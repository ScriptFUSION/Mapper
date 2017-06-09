<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

/**
 * Translates a value using a mapping.
 *
 * @deprecated Use Walk instead.
 */
class Translate extends Decorator
{
    private $mapping;

    /**
     * Initializes this instance with the specified value and mapping.
     *
     * @param Strategy $value Value used to match against an entry in the mapping.
     * @param Strategy|Mapping|array|mixed $mapping Mapping that specifies what the value may be translated to.
     */
    public function __construct(Strategy $value, $mapping)
    {
        parent::__construct($value);

        $this->mapping = $mapping;
    }

    public function __invoke($data, $context = null)
    {
        $value = parent::__invoke($data, $context);
        $mapping = $this->delegate($this->mapping, $data, $context);

        if (is_array($mapping) && array_key_exists($value, $mapping)) {
            return $mapping[$value];
        }
    }
}
