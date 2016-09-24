<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\ArrayWalker\ArrayWalker;

/**
 * Copies a portion of input data.
 */
class Copy implements Strategy
{
    const PATH_SEPARATOR = '->';

    private $path;

    /**
     * Initializes this instance with the specified path.
     *
     * @param array|string $path Array of path components or string of  `->`-delimited components.
     */
    public function __construct($path)
    {
        $this->path = is_array($path) ? $path : explode(self::PATH_SEPARATOR, $path);
    }

    /**
     * @param mixed $data
     * @param mixed $context
     *
     * @return mixed
     */
    public function __invoke($data, $context = null)
    {
        if ($this->path && is_array($data)) {
            return ArrayWalker::walk($data, $this->path);
        }
    }
}
