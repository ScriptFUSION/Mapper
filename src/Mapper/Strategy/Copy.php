<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\ArrayWalker\ArrayWalker;

class Copy implements Strategy
{
    const PATH_SEPARATOR = '->';

    private $path;

    /**
     * Initializes this instance with the specified path.
     *
     * @param string $path Path.
     */
    public function __construct($path)
    {
        $this->path = explode(self::PATH_SEPARATOR, $path);
    }

    /**
     * @param mixed $data
     * @param mixed $context
     *
     * @return mixed
     */
    public function __invoke($data, $context = null)
    {
        if (is_array($data)) {
            return ArrayWalker::walk($data, $this->path);
        }
    }
}
