<?php
namespace ScriptFUSION\Mapper;

trait MapperAwareTrait
{
    use KeyAwareTrait;

    /**
     * @var Mapper
     */
    private $mapper;

    protected function getMapper()
    {
        return $this->mapper;
    }

    public function setMapper(Mapper $mapper)
    {
        $this->mapper = $mapper;

        return $this;
    }
}
