<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\MapperAware;
use ScriptFUSION\Mapper\MapperAwareTrait;
use ScriptFUSION\Mapper\Mapping;

class Walk extends Copy implements MapperAware
{
    use MapperAwareTrait;

    private $strategyOrMapping;

    /**
     * @param Strategy|Mapping|array|mixed $strategyOrMapping
     * @param string $path
     */
    public function __construct($strategyOrMapping, $path)
    {
        parent::__construct($path);

        $this->strategyOrMapping = $strategyOrMapping;
    }

    public function __invoke($data, $context = null)
    {
        return parent::__invoke($this->getMapper()->map($data, $this->strategyOrMapping, $context), $context);
    }
}
