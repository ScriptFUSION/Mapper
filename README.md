Mapper
======

[![Latest version][Version image]][Releases]
[![Build status][Build image]][Build]
[![Test coverage][Coverage image]][Coverage]
[![Code style][Style image]][Style]

Mapper transforms arrays from one format to another using an object composition DSL. An application often receives data from a foreign source structured differently than it wants. We can use Mapper to transform data into a more suitable format by using a `Mapping` as shown in the following example.

```php
$mappedData = (new Mapper)->map($data, new MyMapping);
```

This supposes we already created a mapping, `MyMapping`, to convert the data.

Mappings
--------

Mappings are data transformation descriptions that describe how to convert data from one format to another. Mappings are an object wrapper for an array that describes the output format with instructions (hereafter known as *strategies*) that fetch or augment input data. To write a mapping we must know the input data format so we can then write an array that represents the desired output format and decorate it with strategies.

### Example

In the following simple but contrived example we use a mapping to effectively rename the input array's key from *foo* to *bar*.

```php
$fooData = ['foo' => 123];

class FooToBarMapping extends Mapping
{
    protected function createMapping()
    {
        return ['bar' => new Copy('foo')];
    }
}

$barData = (new Mapper)->map($fooData, new FooToBarMapping);
```

> ['bar' => 123]

In this example we declare a mapping, `FooToBarMapping`, and pass it to the `Mapper::map` method to transform `$fooData` into `$barData`.

This mapping introduces the `Copy` strategy that copies a value from the input data to the output. Strategies are just one type of *expression* we can specify in a mapping.

### Expressions

An expression is a pseudo-type representing the list of valid mapping value types. The keys of a mapping are never modified by Mapper but its values may change depending on the expression type. Following is the list of valid expression types; any other type causes `InvalidExpressionException` to be thrown.

 1. `Strategy`
 2. `Mapping`
 3. Mapping fragment
 4. Scalar
 5. `null`

[Strategies](#strategies) are invoked and substituted as described in the following section. Mappings may contain any number of additional embedded mappings or mapping fragments&mdash;a mapping fragment is just a mapping described by an array instead of a `Mapping` object. Scalar values (*integer*, *float*, *string* and *boolean*) and `null` have no special meaning and are presented verbatim in the output.

### Writing a mapping

To write a mapping create a new class that extends `Mapping` and implement its abstract method, `createMapping()`, that returns an array describing the output format with any combination of valid [expressions](#expressions).

For prototyping purposes we can avoid writing a new mapping class and instead create an `AnonymousMapping`, passing the mapping definition to its constructor, which can be quicker than writing a new class. However, the recommended way to write mappings is to write new classes so mappings have meaningful names to identify them.

It is recommended to name mapping classes *XToYMapping* where *X* is the name of the input format and *Y* is the name of the output format.

Strategies
----------

Strategies are invokable classes that are invoked by Mapper and substituted for their return values. Strategies can be broadly broken down into two categories: fetchers and augmenters. Fetch strategies retrieve data while augmenters change data provided by other strategies.

Strategies are basic building blocks from which complex data manipulation chains can be constructed to meet the bespoke requirements of an application. The composition of strategies forms a powerful object composition DSL that allows us to express how to retrieve and augment data to mould it into the desired format.

For a complete list of strategies please see the [strategy reference](#strategy-reference).

Strategy reference
------------------

The following strategies ship with Mapper and provide a suite of commonly used features, as listed below.

### Strategy index

#### Fetchers

 - [Copy](#copy) &ndash; Copies a portion of input data.
 - [CopyContext](#copycontext) &ndash; Copies a portion of context data.

#### Augmenters

 - [Callback](#callback) &ndash; Augments data using the specified callback.
 - [Collection](#collection) &ndash; Maps a collection of data by applying a transformation to each datum.
 - [Context](#context) &ndash; Replaces the context for the specified expression.
 - [Either](#either) &ndash; Either uses the primary strategy, if it returns non-null, otherwise delegates to a fallback expression.
 - [Filter](#filter) &ndash; Filters null values or values rejected by the specified callback.
 - [Flatten](#flatten) &ndash; Moves all nested values to the top level.
 - [IfExists](#ifexists) &ndash; Delegates to one expression or another depending on whether the specified condition maps to null.
 - [Merge](#merge) &ndash; Merges two data sets together giving precedence to the latter if keys collide.
 - [TakeFirst](#takefirst) &ndash; Takes the first value from a collection one or more times.
 - [ToList](#tolist) &ndash; Converts data to a single-element list unless it is already a list.
 - [Type](#type) &ndash; Casts data to the specified type.
 - [Unique](#unique) &ndash; Creates a collection of unique values by removing duplicates.
 - [Walk](#walk) &ndash; Walks a nested structure to the specified element in the same manner as `Copy`.

### Copy

Copy copies a portion of the input data with support for nested structures.

Copy is probably the most common strategy whether used by itself or injected into other strategies.

#### Signature

```php
Copy(array|string $path)
```

 1. `$path` &ndash; Array of path components or string of  `->`-delimited components.

#### Example

```php
$data = [
    'foo' => [
        'bar' => 123,
    ],
];
	
(new Mapper)->map($data, new Copy('foo'));
```

> ['bar' => 123]

```php
(new Mapper)->map($data, new Copy('foo->bar'));
// or
(new Mapper)->map($data, new Copy(['foo', 'bar']));
```

> 123

### CopyContext

Copies a portion of context data; works exactly the same way as `Copy` in all other respects.

#### Signature

```php
CopyContext(array|string $path)
```

 1. `$path` &ndash; Array of path components or string of  `->`-delimited components.

#### Example

```php
$data = ['foo' => 123];
$context = ['foo' => 456];

(new Mapper)->map($data, new CopyContext('foo'), $context);
```

> 456

### Callback

Augments data using the return value of the specified callback.

It is recommended to only use this for prototyping if passing closures and to later convert such usages into strategies, however it is acceptable to use this strategy with method pointers. This is because strategies and methods both have names whereas closures are anonymous. Strategies are usually preferred since they are reusable.

#### Signature

```php
Callback(callable $callback)
```

 1. `$callback` &ndash; Callback function that receives mapping data as its first argument and context as its second.

#### Example

```php
(new Mapper)->map(
    range(1, 5),
    new Callback(
        function ($data) {
            $total = 0;

            foreach ($data as $number) {
                $total += $number;
            }

            return $total;
        }
    )
);
```

> 15

### Collection

Maps a collection of data by applying a transformation to each datum using a callback. The data collection must be an expression that maps to an array otherwise null is returned.

#### Signature

```php
Collection(Strategy|Mapping|array|mixed $collection, Strategy|Mapping|array|mixed $transformation)
```

 1. `$collection` &ndash; Expression that maps to an array.
 2. `$transformation` &ndash; Transformation expression. The current datum is passed as context.

#### Example

```php
(new Mapper)->map(
    ['foo' => range(1, 5)],
    new Collection(
        new Copy('foo'),
        new Callback(
            function ($data, $context) {
                return $context * 2;
            }
        )
    )
);
```

> [2, 4, 6, 8, 10]

### Context

Replaces the context for the specified expression.

#### Signature

```php
Context(Strategy|Mapping|array|mixed $expression, Strategy|Mapping|array|mixed $context)
```

 1. `$expression` &ndash; Expression.
 2. `$context` &ndash; New context.

#### Example

```php
(new Mapper)->map(
    ['foo' => 123],
    new Context(
        new CopyContext('foo'),
        ['foo' => 456]
    ),
    ['foo' => 789]
);
```

> 456

### Either

Either uses the primary strategy, if it returns non-null, otherwise delegates to a fallback expression.

#### Signature

```php
Either(Strategy $strategy, Strategy|Mapping|array|mixed $expression)
```

 1. `$strategy` &ndash; Primary strategy.
 2. `$expression` &ndash; Fallback expression.

#### Example
```php
(new Mapper)->map(
    ['bar' => 'bar'],
    new Either(new Copy('foo'), new Copy('bar'))
);
```

> 'bar'

### Filter

Filters null values or values rejected by the specified callback.

#### Signature

```php
Filter(Strategy|Mapping|array|mixed $expression, callable $callback = null)
```

 1. `$expression` &ndash; Expression.
 2. `$callback` &ndash; Callback function that receives the current value as its first argument.

#### Example

```php
(new Mapper)->map(
    ['foo' => range(1, 10)],
    new Filter(
        new Copy('foo'),
        function ($value) {
            return $value % 2;
        }
    )
);
```

> [1, 3, 5, 7, 9]

### Flatten

Moves all nested values to the top level.

#### Signature

```php
Flatten(Strategy|Mapping|array|mixed $expression)
```

 1. `$expression` &ndash; Expression.

#### Methods

 - `ignoreKeys($ignore = true)` &ndash; When true, only considers values when merging, otherwise duplicate keys replace each other with the last visited key taking precedence. Defaults to *false* to preserve keys.

#### Example

```php
$data = [
    'foo' => [
        range(1, 3),
        'bar' => [range(3, 5)],
    ],
];

(new Mapper)->map($data, new Flatten(new Copy('foo')));
```

> [3, 4, 5]

```php
(new Mapper)->map($data, (new Flatten(new Copy('foo')))->ignoreKeys());
```

> [1, 2, 3, 3, 4, 5]

### IfExists

Delegates to one expression or another depending on whether the specified condition maps to null.

#### Signature

```php
IfExists(Strategy $condition, Strategy|Mapping|array|mixed $if, Strategy|Mapping|array|mixed $else = null)
```

 1. `$condition` &ndash; Condition.
 2. `$if` &ndash; Expression used when condition maps to non-null.
 3. `$else` &ndash; Expression used when condition maps to null.

#### Example

```php
$data = ['foo' => 'foo'];

(new Mapper)->map($data, new IfExists(new Copy('foo'), true, false));
```

> true

```php
(new Mapper)->map($data, new IfExists(new Copy('bar'), true, false));
```

> false

### Merge

Merges two data sets together giving precedence to the latter if string keys collide; integer keys never collide. For more information see [array_merge](http://php.net/manual/en/function.array-merge.php).

#### Signature

```php
Merge(Strategy|Mapping|array|mixed $first, Strategy|Mapping|array|mixed $second)
```

 1. `$first` &ndash; First data set.
 2. `$second` &ndash; Second data set.

#### Example

```php
(new Mapper)->map(
    [
        'foo' => range(1, 3),
        'bar' => range(3, 5),
    ],
    new Merge(new Copy('foo'), new Copy('bar'))
);
```

> [1, 2, 3, 3, 4, 5]

### TakeFirst

Takes the first value from a collection one or more times according to the specified depth. If the depth exceeds the number of nesting levels of the collection the last item encountered will be returned.

#### Signature

```php
TakeFirst(Strategy|Mapping|array|mixed $collection, int $depth = 1)
```

 1. `$collection` &ndash; Expression that maps to an array.
 2. `$depth` &ndash; Number of times to descending into nested collections.

#### Example

```php
(new Mapper)->map(
    [
        'foo' => [
            'bar' => [
                'baz' => 123,
                'quz' => 456,
            ],
        ],
    ],
    new TakeFirst(new Copy('foo'), 2)
);
```

> 123

### ToList

Converts data to a single-element list unless it is already a list. A list is defined as an array with contiguous integer keys.

This was created because some formats represent single-value lists as the bare value instead of a list containing just that value. This strategy ensures the expression is always a list by wrapping it in an array if it is not already a list.

#### Signature

```php
ToList(Strategy|Mapping|array|mixed $expression)
```

 1. `$expression` &ndash; Expression.

#### Example

```php
(new Mapper)->map(['foo' => 'bar'], new ToList(new Copy('foo')));
```

> ['bar']

### Type

Casts data to the specified type.

#### Signature

```php
Type(DataType $type, Strategy $strategy)
```

 1. `$type` &ndash; Type to cast to.
 2. `$stategy` &ndash; Strategy.

#### Example

```php
(new Mapper)->map(['foo' => 123], new Type(DataType::STRING(), new Copy('foo')));
```

> '123'

### Unique

Creates a collection of unique values by removing duplicates.

#### Signature

```php
Unique(Strategy|Mapping|array|mixed $collection)
```

 1. `$collection` &ndash; Expression the maps to an array.

#### Example

```php
(new Mapper)->map(
    ['foo' => array_merge(range(1, 3), range(3, 5))],
    new Unique(new Copy('foo'))
);
```

> [1, 2, 3, 4, 5]

### Walk

Walks a nested structure to the specified element in the same manner as [`Copy`](#copy).

#### Signature

```php
Walk(Strategy|Mapping|array|mixed $expression, array|string $path)
```

 1. `$expression` &ndash; Expression.
 2. `$path` Array of path components or string of  `->`-delimited components.

#### Example

```php
(new Mapper)->map(
    [
        'foo' => [
            'bar' => [
                'baz' => 123,
            ],
        ],
    ],
    new Walk(new Copy('foo'), 'bar->baz')
)
```

> 123

### Writing strategies

Strategies must implement the `Strategy` interface but it is common to extend `Delegate` or `Decorator` because we usually write augmenters which expect another strategy injected into them to provide data. `Delegate` and `Decorator` provide the `delegate()` method, which allows a strategy to evaluate an expression using Mapper, and is usually needed to evaluate the injected strategy. `Delegate` can delegate any expression to Mapper whereas `Decorator` only accepts `Strategy` objects.

It is recommended to name custom strategies with a *Strategy* suffix to help distinguish them from stock strategies.

Requirements
------------

 - [PHP 5.5](http://php.net/)
 - [Composer](https://getcomposer.org/)

Testing
-------

Mapper is fully unit tested. Run the tests with `bin/test` from a shell. All examples
in this document can be found in `DocumentationTest`.

Limitations
-----------

 - Strategies do not know the name of the key they are assigned to because `Mapper` does not forward the key name.
 - Strategies do not know where they sit in a `Mapping` and therefore cannot traverse a mapping relative to their position.
 - The `Collection` strategy overwrites context making any previous context inaccessible to descendants.


  [Releases]: https://github.com/ScriptFUSION/Mapper/releases
  [Version image]: https://poser.pugx.org/scriptfusion/mapper/version "Latest version"
  [Build]: http://travis-ci.org/ScriptFUSION/Mapper
  [Build image]: https://travis-ci.org/ScriptFUSION/Mapper.svg "Build status"
  [Coverage]: https://coveralls.io/github/ScriptFUSION/Mapper
  [Coverage image]: https://coveralls.io/repos/ScriptFUSION/Mapper/badge.svg "Test coverage"
  [Style]: https://styleci.io/repos/59734709
  [Style image]: https://styleci.io/repos/59734709/shield?style=flat "Code style"
