<?php
namespace ScriptFUSION\Mapper;

interface MapperAware extends KeyAware
{
    /**
     * Sets the mapper to the specified mapper instance.
     *
     * @param Mapper $mapper Mapper instance.
     *
     * @return $this
     */
    public function setMapper(Mapper $mapper);
}
