<?php
namespace ScriptFUSION;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Specifies a PHP data type.
 */
class DataType extends AbstractEnumeration
{
    const BOOLEAN = 'boolean';
    const INTEGER = 'integer';
    const FLOAT = 'float';
    const STRING = 'string';
    const MAP = 'array';
    const OBJECT = 'object';
}
