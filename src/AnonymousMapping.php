<?php
namespace ScriptFUSION\Mapper;

use ScriptFUSION\Mapper\Strategy\Strategy;

final class AnonymousMapping extends Mapping
{
    private $definition;

    /**
     * @param array|Strategy $definition
     */
    public function __construct($definition)
    {
        $this->definition = $definition;

        parent::__construct();
    }

    protected function createMapping()
    {
        return $this->definition;
    }
}
