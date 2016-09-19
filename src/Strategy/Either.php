<?php
namespace ScriptFUSION\Mapper\Strategy;

class Either extends Decorator
{
    private $strategyOrMapping;

    public function __construct(Strategy $strategy, $strategyOrMapping)
    {
        parent::__construct($strategy);

        $this->strategyOrMapping = $strategyOrMapping;
    }

    public function __invoke($data, $context = null)
    {
        if (($result = parent::__invoke($data, $context)) !== null) {
            return $result;
        }

        return $this->delegate($this->strategyOrMapping, $data, $context);
    }
}
