<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

/**
 * Copies a portion of context data.
 */
class CopyContext extends Copy
{
    private $walk;

    /**
     * {@inheritdoc}
     *
     * @param Strategy|Mapping|array|mixed $path Array of path components, string of `->`-delimited path components or
     *     a strategy or mapping resolving to such an expression.
     */
    public function __construct($path = null)
    {
        parent::__construct($path);

        $this->walk = (bool)$path;
    }

    public function __invoke($record, $context = null)
    {
        if ($this->walk) {
            return parent::__invoke($context);
        }

        return $context;
    }
}
