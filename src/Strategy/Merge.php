<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

/**
 * Merges two data sets together giving precedence to the latter if keys collide.
 */
class Merge extends Delegate
{
    private $second;

    /**
     * @param Strategy|Mapping|array|mixed $first First data set.
     * @param Strategy|Mapping|array|mixed $second Second data set.
     */
    public function __construct($first, $second)
    {
        parent::__construct($first);

        $this->second = $second;
    }

    public function __invoke($data, $context = null)
    {
        return array_merge(
            (array)parent::__invoke($data, $context),
            (array)$this->delegate($this->second, $data, $context)
        );
    }
}
