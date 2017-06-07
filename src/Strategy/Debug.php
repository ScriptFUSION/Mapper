<?php
namespace ScriptFUSION\Mapper\Strategy;

/**
 * Debugs a mapping by breaking the debugger wherever this strategy is inserted.
 */
final class Debug extends Delegate
{
    public function __construct($expression = null)
    {
        parent::__construct($expression);
    }

    public function __invoke($data, $context = null)
    {
        $mapped = parent::__invoke($data, $context);

        self::debug($data, $context, $mapped);

        return $mapped;
    }

    // Although all these parameters are unused, it is helpful to have relevant data in the current stack frame.
    private static function debug($data, $context, $mapped)
    {
        if (function_exists('xdebug_break')) {
            xdebug_break();
        }
    }
}
