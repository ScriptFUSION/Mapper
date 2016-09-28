<?php
namespace ScriptFUSION\Mapper;

use ScriptFUSION\Mapper\Strategy\Strategy;

/**
 * Maps records according to expression value types.
 */
class Mapper
{
    /**
     * @param array $record
     * @param Strategy|Mapping|array|mixed $expression
     * @param mixed $context
     *
     * @return mixed
     *
     * @throws InvalidExpressionException An invalid strategy or mapping object was specified.
     */
    public function map(array $record, $expression, $context = null)
    {
        /* Strategy. */
        if ($expression instanceof Strategy) {
            return $this->mapStrategy($record, $expression, $context);
        } /* Mapping. */
        elseif ($expression instanceof Mapping) {
            return $this->mapMapping($record, $expression, $context);
        } /* Mapping fragment. */
        elseif (is_array($expression)) {
            return $this->mapFragment($record, $expression, $context);
        } /* Null or scalar values. */
        elseif (null === $expression || is_scalar($expression)) {
            return $expression;
        }

        throw new InvalidExpressionException('Invalid strategy or mapping: "' . get_class($expression) . '".');
    }

    /**
     * @param array $record Record.
     * @param Mapping $mapping Mapping.
     * @param mixed $context Contextual data.
     *
     * @return array Mapped record.
     *
     * @throws \Exception
     */
    protected function mapMapping(array $record, Mapping $mapping, $context = null)
    {
        $mapped = $this->mapFragment($record, $mapping->toArray(), $context);

        if ($mapping->isWrapped()) {
            // Unwrap.
            return $mapped[0];
        }

        return $mapped;
    }

    protected function mapFragment(array $record, array $fragment, $context = null)
    {
        if (array_walk(
            $fragment,
            function (&$strategy, $key, array $record) use ($context) {
                $strategy = $this->map($record, $strategy, $context);
            },
            $record
        )) {
            return $fragment;
        }

        throw new \Exception; // TODO: Determine whether this statement is reachable.
    }

    /**
     * @param array $record
     * @param Strategy $strategy
     * @param mixed $context
     *
     * @return mixed
     */
    protected function mapStrategy(array $record, Strategy $strategy, $context = null)
    {
        $this->injectDependencies($strategy);

        return $strategy($record, $context);
    }

    /**
     * @param object $object
     */
    protected function injectDependencies($object)
    {
        if ($object instanceof MapperAware) {
            $object->setMapper($this);
        }
    }
}
