<?php
namespace ScriptFUSION\Mapper\Strategy;

class Flatten extends Delegate
{
    private $ignoreKeys = false;

    public function ignoreKeys($ignore = true)
    {
        $this->ignoreKeys = $ignore;

        return $this;
    }

    public function __invoke($data, $context = null)
    {
        return iterator_to_array($this->flatten(parent::__invoke($data, $context)), !$this->ignoreKeys);
    }

    private function flatten($data)
    {
        foreach (new \RecursiveIteratorIterator(new \RecursiveArrayIterator($data)) as $key => $value) {
            yield $key => $value;
        }
    }
}
