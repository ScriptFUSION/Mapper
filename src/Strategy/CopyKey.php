<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\KeyAware;
use ScriptFUSION\Mapper\KeyAwareTrait;

/**
 * Copies the current key.
 */
class CopyKey implements Strategy, KeyAware
{
    use KeyAwareTrait;

    public function __invoke($record, $context = null)
    {
        return $this->key;
    }
}
