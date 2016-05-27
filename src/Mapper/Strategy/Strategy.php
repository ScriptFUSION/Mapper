<?php
namespace ScriptFUSION\Mapper\Strategy;

interface Strategy
{
    /**
     * @param mixed $data
     * @param mixed $context
     *
     * @return mixed
     */
    public function __invoke($data, $context = null);
}
