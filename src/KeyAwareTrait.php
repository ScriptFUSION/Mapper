<?php
namespace ScriptFUSION\Mapper;

trait KeyAwareTrait
{
    /**
     * @var string|int
     */
    private $key;

    public function setKey($key)
    {
        $this->key = $key;
    }
}
