<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapping;

class TakeFirst extends Delegate
{
    private $depth;

    /**
     * @param Strategy|Mapping|array|mixed $strategyOrMapping
     * @param int $depth
     */
    public function __construct($strategyOrMapping, $depth = 1)
    {
        parent::__construct($strategyOrMapping);

        $this->depth = $depth;
    }

    public function __invoke($data, $context = null)
    {
        $depth = $this->depth;
        $structure = parent::__invoke($data, $context);

        while (is_array($structure) && $depth--) {
            $structure = reset($structure);
        }

        return $structure;
    }
}
