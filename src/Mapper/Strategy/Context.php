<?php
namespace ScriptFUSION\Mapper\Strategy;

class Context extends Decorator
{
    private $strategyOrMapping;

    public function __construct(Strategy $strategy, $strategyOrMapping)
    {
        parent::__construct($strategy);

        $this->strategyOrMapping = $strategyOrMapping;
    }

    public function __invoke($data, $context = null)
    {
        return parent::__invoke($data, $this->delegate($this->strategyOrMapping, $data, $context));
    }
}
