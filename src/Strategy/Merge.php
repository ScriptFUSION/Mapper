<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

class Merge extends Delegate
{
    private $second;

    /**
     * @param Strategy|Mapping|array|mixed $first
     * @param Strategy|Mapping|array|mixed $second
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
