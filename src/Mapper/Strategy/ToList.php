<?php
namespace ScriptFUSION\Mapper\Strategy;

/**
 * Converts data to a single-element list unless it is already a list.
 */
class ToList extends Delegate
{
    public function __invoke($data, $context = null)
    {
        $data = parent::__invoke($data, $context);

        $list = is_array($data) ? array_values($data) : null;

        // Data is not a list.
        if ($list !== $data) {
            // Wrap.
            return [$data];
        }

        // Data is a list.
        return $data;
    }
}
