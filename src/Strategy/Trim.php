<?php
declare(strict_types=1);

namespace ScriptFUSION\Mapper\Strategy;

class Trim extends Delegate
{
    public function __invoke($data, $context = null)
    {
        return trim(parent::__invoke($data, $context));
    }
}
