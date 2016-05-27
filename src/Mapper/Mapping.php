<?php
namespace ScriptFUSION\Mapper;

abstract class Mapping extends \ArrayObject
{
    public function __construct()
    {
        parent::__construct($this->createMap());
    }

    abstract protected function createMap();
}
