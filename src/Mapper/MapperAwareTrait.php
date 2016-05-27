<?php
namespace ScriptFUSION\Mapper;

trait MapperAwareTrait
{
    /** @var Mapper */
    private $mapper;

    public function setMapper(Mapper $mapper)
    {
        $this->mapper = $mapper;

        return $this;
    }

    protected function getMapper()
    {
        return $this->mapper;
    }
}
