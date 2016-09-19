<?php
namespace ScriptFUSION\Mapper;

final class AnonymousMapping extends Mapping
{
    private $definition;

    public function __construct(array $definition)
    {
        $this->definition = $definition;

        parent::__construct();
    }

    protected function createMapping()
    {
        return $this->definition;
    }
}
