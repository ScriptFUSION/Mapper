<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\ArrayWalker\ArrayWalker;

/**
 * Copies a portion of input data.
 */
class Copy extends Delegate
{
    const PATH_SEPARATOR = '->';

    /**
     * @param mixed $record
     * @param mixed $context
     *
     * @return mixed
     */
    public function __invoke($record, $context = null)
    {
        if (!is_array($record)) {
            return null;
        }

        if (!is_array($path = parent::__invoke($record, $context))) {
            $path = explode(self::PATH_SEPARATOR, $path);
        }

        return $path ? ArrayWalker::walk($record, $path) : null;
    }
}
