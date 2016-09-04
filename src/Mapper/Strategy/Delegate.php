<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\MapperAware;
use ScriptFUSION\Mapper\MapperAwareTrait;
use ScriptFUSION\Mapper\Mapping;

abstract class Delegate implements Strategy, MapperAware
{
    use MapperAwareTrait;

    private $strategyOrMapping;

    /**
     * @param Strategy|Mapping|array|mixed $strategyOrMapping
     */
    public function __construct($strategyOrMapping)
    {
        $this->strategyOrMapping = $strategyOrMapping;
    }

    public function __invoke($data, $context = null)
    {
        return $this->delegate($this->strategyOrMapping, $data, $context);
    }

    protected function delegate($strategy, $data, $context)
    {
        return $this->mapper->map($data, $strategy, $context);
    }
}
