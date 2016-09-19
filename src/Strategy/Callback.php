<?php
namespace ScriptFUSION\Mapper\Strategy;

class Callback implements Strategy
{
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function __invoke($data, $context = null)
    {
        return call_user_func($this->callback, $data, $context);
    }
}
