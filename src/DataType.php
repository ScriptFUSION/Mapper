<?php
namespace ScriptFUSION\Mapper;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Specifies a PHP data type.
 *
 * @method static BOOLEAN()
 * @method static INTEGER()
 * @method static FLOAT()
 * @method static STRING()
 * @method static MAP()
 * @method static OBJECT()
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
