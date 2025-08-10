<?php
declare(strict_types=1);

namespace ScriptFUSION\Mapper\Strategy;

/**
 * Captures a portion of a string using regular expression matching.
 */
final class Regex extends Delegate
{
    public function __construct(
        $expression,
        private readonly string $regex,
        private readonly int|array $capturingGroup = 0,
    ) {
        parent::__construct($expression);
    }

    public function __invoke($data, $context = null)
    {
        if (preg_match($this->regex, parent::__invoke($data, $context), $matches)) {
            if (is_array($this->capturingGroup)) {
                return array_values(array_intersect_key($matches, array_flip($this->capturingGroup)));
            }

            return $matches[$this->capturingGroup];
        }
    }
}
