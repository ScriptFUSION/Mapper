<?php
namespace ScriptFUSION\Mapper;

/**
 * Represents an expression value.
 */
final class Expression
{
    private $expression;

    public function __construct($expression)
    {
        $this->expression = "$expression";
    }

    public function __toString()
    {
        return $this->expression;
    }
}
