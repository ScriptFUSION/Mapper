<?php
namespace ScriptFUSION\Mapper\Strategy;

/**
 * Creates a collection of unique values by removing duplicates.
 */
class Unique extends Delegate
{
    public function __invoke($data, $context = null)
    {
        return array_unique(parent::__invoke($data, $context));
    }
}
