<?php
namespace ScriptFUSION\Mapper\Strategy;

class Filter extends Delegate
{
    private $callback;

    public function __construct($strategyOrMapping, callable $callback = null)
    {
        parent::__construct($strategyOrMapping);

        $this->callback = $callback;
    }

    public function __invoke($data, $context = null)
    {
        if (!is_array($data = parent::__invoke($data, $context))) {
            return null;
        }

        return array_filter($data, $this->callback ?: function ($value) {
            return $value !== null;
        });
    }
}
