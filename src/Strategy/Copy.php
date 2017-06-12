<?php
namespace ScriptFUSION\Mapper\Strategy;

use ScriptFUSION\ArrayWalker\ArrayWalker;
use ScriptFUSION\Mapper\Mapping;

/**
 * Copies a portion of input data, or specified data, according to a lookup path.
 */
class Copy extends Delegate
{
    const PATH_SEPARATOR = '->';

    private $data;

    /**
     * Initializes this instance with the specified path. If data is specified it is always used instead of input data.
     *
     * @param Strategy|Mapping|array|mixed $path Array of path components, string of `->`-delimited path components or
     *     a strategy or mapping resolving to such an expression.
     * @param Strategy|Mapping|array|mixed $data Optional. Array data or an expression that resolves to an array to be
     *     copied instead of input data.
     */
    public function __construct($path, $data = null)
    {
        parent::__construct($path);

        $this->data = $data;
    }

    /**
     * @param mixed $record
     * @param mixed $context
     *
     * @return mixed
     */
    public function __invoke($record, $context = null)
    {
        // It is typically an error for record not to be an array but we prefer to avoid throwing exceptions.
        if (!is_array($record)) {
            return null;
        }

        // Resolve the path expression. Path will always be an array after this block.
        if (!is_array($path = parent::__invoke($record, $context))) {
            // If it's not an array treat it as a delimited string; implicitly casts other scalar types.
            $path = explode(self::PATH_SEPARATOR, $path);
        }

        // Overwrite record with resolved data expression if set and ensure it is an array.
        if ($this->data !== null && !is_array($record = $this->delegate($this->data, $record, $context))) {
            return null;
        }

        // Walk path unless it is empty.
        return $path ? ArrayWalker::walk($record, $path) : null;
    }
}
