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
     * @param mixed $data
     * @param mixed $context
     *
     * @return mixed
     */
    public function __invoke($data, $context = null)
    {
        if (!is_array($data)) {
            return null;
        }

        if (!is_array($path = parent::__invoke($data, $context))) {
            $path = explode(self::PATH_SEPARATOR, $path);
        }

        return $path ? ArrayWalker::walk($data, $path) : null;
    }
}
