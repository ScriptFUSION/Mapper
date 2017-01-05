<?php
namespace ScriptFUSION\Mapper;

interface KeyAware
{
    /**
     * Sets the key to the specified value.
     *
     * @param string|int $key Key.
     *
     * @return void
     */
    public function setKey($key);
}
