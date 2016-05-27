<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\DataType;

class Type extends Decorator
{
    private $type;

    public function __construct(DataType $type, Strategy $strategy)
    {
        parent::__construct($strategy);

        $this->type = $type;
    }

    public function __invoke($data, $context = null)
    {
        $data = parent::__invoke($data, $context);

        if (settype($data, "$this->type")) {
            return $data;
        }

        $type = gettype($data);
        throw new \RuntimeException("Could not convert \"$type\" to \"$this->type\".");
    }
}
