<?php
namespace ScriptFUSION\Mapper;

use ScriptFUSION\Mapper\Strategy\Strategy;

/**
 * Maps records according to expression value types.
 */
class Mapper
{
    /**
     * Maps the specified record according to the specified expression type. Optionally, used-defined contextual data
     * may be passed to the expression. The record key is for internal use only and represents the current array key.
     *
     * May be called recursively if the expression embeds more expressions.
     *
     * @param array $record Record.
     * @param Strategy|Mapping|array|mixed $expression Expression.
     * @param mixed $context Optional. Contextual data.
     * @param string|int|null $key Internal. Record key.
     *
     * @return mixed
     *
     * @throws InvalidExpressionException An invalid strategy or mapping object was specified.
     */
    public function map(array $record, $expression, $context = null, $key = null)
    {
        /* Strategy. */
        if ($expression instanceof Strategy) {
            return $this->mapStrategy($record, $expression, $context, $key);
        } /* Mapping. */
        elseif ($expression instanceof Mapping) {
            return $this->mapMapping($record, $expression, $context, $key);
        } /* Mapping fragment. */
        elseif (is_array($expression)) {
            return $this->mapFragment($record, $expression, $context, $key);
        } /* Null or scalar values. */
        elseif (null === $expression || is_scalar($expression)) {
            return $expression;
        }

        throw new InvalidExpressionException('Invalid strategy or mapping: "' . get_class($expression) . '".');
    }

    /**
     * @param array $record Record.
     * @param Mapping $mapping Mapping.
     * @param mixed $context Optional. Contextual data.
     * @param string|int|null $key Internal. Record key.
     *
     * @return array Mapped record.
     *
     * @throws \Exception
     */
    protected function mapMapping(array $record, Mapping $mapping, $context = null, $key = null)
    {
        $mapped = $this->mapFragment($record, $mapping->toArray(), $context, $key);

        if ($mapping->isWrapped()) {
            // Unwrap.
            return $mapped[0];
        }

        return $mapped;
    }

    /**
     * @param array $record Record.
     * @param array $fragment Mapping.
     * @param null $context Optional. Contextual data.
     * @param string|int|null $key Internal. Record key.
     *
     * @return array Mapped record.
     *
     * @throws \Exception Mapping failed for an unknown reason.
     */
    protected function mapFragment(array $record, array $fragment, $context = null, $key = null)
    {
        if (array_walk(
            $fragment,
            // Mapping fragment keys are not useful because they are hard-coded.
            function (&$expression, $_, array $record) use ($context, $key) {
                $expression = $this->map($record, $expression, $context, $key);
            },
            $record
        )) {
            return $fragment;
        }

        throw new \Exception; // TODO: Determine whether this statement is reachable.
    }

    /**
     * @param array $record Record.
     * @param Strategy $strategy Strategy.
     * @param mixed $context Optional. Contextual data.
     * @param string|int|null $key Internal. Record key.
     *
     * @return mixed
     */
    protected function mapStrategy(array $record, Strategy $strategy, $context = null, $key = null)
    {
        $strategy instanceof KeyAware && $strategy->setKey($key);

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
