<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\MapperAware;
use ScriptFUSION\Mapper\MapperAwareTrait;
use ScriptFUSION\Mapper\Mapping;

abstract class Delegate implements Strategy, MapperAware
{
    use MapperAwareTrait;

    private $expression;

    /**
     * @param Strategy|Mapping|array|mixed $expression
     */
    public function __construct($expression)
    {
        $this->expression = $expression;
    }

    public function __invoke($data, $context = null)
    {
        return $this->delegate($this->expression, $data, $context);
    }

    protected function delegate($strategy, $data, $context)
    {
        return $this->mapper->map($data, $strategy, $context);
    }
}
