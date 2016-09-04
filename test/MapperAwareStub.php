<?php
namespace ScriptFUSIONTest;

use ScriptFUSION\Mapper\MapperAwareTrait;

final class MapperAwareStub
{
    use MapperAwareTrait;

    public function getMapperPublic()
    {
        return $this->getMapper();
    }
}
