<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\Mapper\Expression;
use ScriptFUSION\Mapper\Mapping;

/**
 * Replaces all occurrences of one or more substrings.
 */
class Replace extends Delegate
{
    private $searches;

    private $replacements;

    /**
     * Initializes this instance with the specified expression to search in, search strings and replacement strings.
     *
     * Any number of searches and replacements can be specified. Searches and replacements are parsed in pairs. If no
     * replacements are specified, all matches are removed instead of replaced. If fewer replacements than searches are
     * specified, the last replacement will be used for the remaining searches. If more replacements than searches are
     * specified, the extra replacements will be ignored.
     *
     * @param Strategy|Mapping|array|mixed $expression Expression to search in.
     * @param $searches string|Expression|array Search string(s).
     * @param $replacements string|string[]|null Optional. Replacement string(s).
     */
    public function __construct($expression, $searches, $replacements = null)
    {
        parent::__construct($expression);

        $this->searches = is_object($searches) ? [$searches] : (array)$searches;
        $this->replacements = (array)$replacements;
    }

    public function __invoke($data, $context = null)
    {
        $output = parent::__invoke($data, $context);
        $replacements = $this->replacements;
        $replace = null;

        foreach ($this->searches as $search) {
            $replace = count($replacements) ? array_shift($replacements) : (string)$replace;

            if ($search instanceof Expression) {
                $output = preg_replace($search, $replace, $output);
            } else {
                $output = str_replace($search, $replace, $output);
            }
        }

        return $output;
    }
}
