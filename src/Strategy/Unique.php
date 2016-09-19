<?php
namespace ScriptFUSION\Mapper\Strategy;

class Unique extends Delegate
{
    public function __invoke($data, $context = null)
    {
        return array_unique(parent::__invoke($data, $context));
    }
}
