<?php
namespace ScriptFUSION\Mapper\Strategy;

class CopyContext extends Copy
{
    private $walk;

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
