<?php
namespace ScriptFUSION\Mapper;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Specifies a PHP data type.
 *
 * @method static self BOOLEAN
 * @method static self INTEGER
 * @method static self FLOAT
 * @method static self STRING
 * @method static self MAP
 * @method static self OBJECT
 */
final class DataType extends AbstractEnumeration
{
    const BOOLEAN = 'boolean';
    const INTEGER = 'integer';
    const FLOAT = 'float';
    const STRING = 'string';
    const MAP = 'array';
    const OBJECT = 'object';
}
