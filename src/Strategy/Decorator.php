<?php
namespace ScriptFUSION\Mapper\Strategy;

abstract class Decorator extends Delegate
{
    private $strategy;

    public function __construct(Strategy $strategy)
    {
        parent::__construct($strategy);

        $this->strategy = $strategy;
    }

    protected function getStrategy()
    {
        return $this->strategy;
    }
}
