<?php
namespace ScriptFUSION\Mapper\Strategy;

/**
 * Copies a portion of context data.
 */
class CopyContext extends Copy
{
    private $walk;

    /**
     * {@inheritdoc}
     *
     * @param array|string $path Array of path components or string of  `->`-delimited components.
     */
    public function __construct($path = null)
    {
        parent::__construct($path);

        $this->walk = !!$path;
    }

    public function __invoke($data, $context = null)
    {
        if ($this->walk) {
            return parent::__invoke($context);
        }

        return $context;
    }
}
