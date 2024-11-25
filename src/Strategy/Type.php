<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\DataType;

/**
 * Casts data to the specified type.
 */
class Type extends Decorator
{
    private $type;

    /**
     * @param DataType $type Type to cast to.
     * @param Strategy $strategy Strategy.
     */
    public function __construct(DataType $type, Strategy $strategy)
    {
        parent::__construct($strategy);

        $this->type = $type;
    }

    public function __invoke($data, $context = null)
    {
        $data = parent::__invoke($data, $context);

        /**
         * settype() only returns false when the type specifier is invalid or
         * "resource". Since the enumeration guarantees valid type specifiers
         * this function call never returns false, so we do not check it.
         */
        settype($data, $this->type->name);

        return $data;
    }
}
