<?php
namespace ScriptFUSION\Mapper\Strategy;

class Flatten extends Delegate
{
    public function __invoke($data, $context = null)
    {
        return iterator_to_array($this->flatten(parent::__invoke($data, $context)));
    }

    private function flatten($data)
    {
        foreach (new \RecursiveIteratorIterator(new \RecursiveArrayIterator($data)) as $key => $value) {
            yield $key => $value;
        }
    }
}
