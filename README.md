Mapper
======

[![Latest version][Version image]][Releases]
[![Total downloads][Downloads image]][Downloads]
[![Build status][Build image]][Build]
[![Test coverage][Coverage image]][Coverage]
[![Code style][Style image]][Style]

Mapper transforms arrays from one format to another using an object composition DSL. An application often receives data from a foreign source structured differently than it wants. We can use Mapper to transform foreign data into a more suitable format for our application using a `Mapping` as shown in the following example.

```php
$mappedData = (new Mapper)->map($data, new MyMapping);
```

This supposes we already created a mapping, `MyMapping`, to convert `$data` into `$mappedData`.

Contents
--------

  1. [Mappings](#mappings)
  1. [Strategies](#strategies)
  1. [Practical example](#practical-example)
  1. [Strategy reference](#strategy-reference)
    1. [Copy](#copy)
    1. [CopyContext](#copycontext)
    1. [CopyKey](#copykey)
    1. [Callback](#callback)
    1. [Collection](#collection)
    1. [Context](#context)
    1. [Either](#either)
    1. [Filter](#filter)
    1. [Flatten](#flatten)
    1. [IfExists](#ifexists)
    1. [Merge](#merge)
    1. [TakeFirst](#takefirst)
    1. [ToList](#tolist)
    1. [TryCatch](#trycatch)
    1. [Type](#type)
    1. [Unique](#unique)
    1. [Walk](#walk)
  1. [Requirements](#requirements)
  1. [Limitations](#limitations)
  1. [Testing](#testing)

Mappings
--------

Mappings are data transformation descriptions that describe how to convert data from one format to another. Mappings are an object wrapper for an array, which describes the output format, with [expressions](#expressions) that can fetch and augment input data. To write a mapping we must know the input data format so we can write an array that represents the desired output format and decorate it with expressions to transform the input data.

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

In this example we declare a mapping, `FooToBarMapping`, and pass it to the `Mapper::map` method to transform `$fooData` into `$barData`. As mentioned, this is just a contrived example to demonstrate how Mapper works; one may like to see a more [practical example](#practical-example).

This mapping introduces the `Copy` strategy that copies a value from the input data to the output. Strategies are just one type of *expression* we can specify as mapping values.

### Expressions

An expression is a pseudo-type representing the list of valid mapping value types. The keys of a mapping are never modified by Mapper but its values may change depending on the expression type. Following is the list of valid expression types; any other type causes `InvalidExpressionException` to be thrown.

 1. `Strategy`
 2. `Mapping`
 3. Mapping fragment
 4. Scalar
 5. `null`

[Strategies](#strategies) are invoked and substituted as described in the following section. Mappings may contain any number of additional embedded mappings or mapping fragments&mdash;a mapping fragment is just a mapping described by an array instead of a `Mapping` object. Scalar values (*integer*, *float*, *string* and *boolean*) and `null` have no special meaning and are presented verbatim in the output.

### Writing a mapping

To write a mapping create a new class that extends `Mapping` and implement its abstract method, `createMapping()`, that returns a strategy or an array describing the output format with any combination of [expressions](#expressions).

For prototyping purposes we can avoid writing a new mapping class and instead create an `AnonymousMapping`, passing the mapping definition to its constructor, which can be quicker than writing a new class. However, the recommended way to write mappings is to write new classes so mappings have meaningful names to identify them.

It is recommended to name mapping classes *XToYMapping* where *X* is the name of the input format and *Y* is the name of the output format.

### Strategy-based mappings

*Strategy-based* mappings are created by specifying a strategy at the top level. Usually mappings are *array-based*, and although such mappings may contain other expressions, including strategies, at the top level they are an array.

Some problems can only be solved with strategy-based mappings. For example, suppose we want to create a mapping that combines two other mappings at the top level. With array-based mappings the best we can do is something like the following.

```php
protected function createMapping()
{
    return [
       'foo' => new FooMapping,
       'bar' => new BarMapping,
    ]
}
```

This composes `FooMapping` and `BarMapping` in our mapping but each mapping will be mapped under new `foo` and `bar` keys respectively. What we really want is to combine the keys of each mapping together at the top level of our mapping but there is no way to express a solution to this problem with array-based mappings. If we use the [`Merge`](#merge) strategy as the basis of our mapping we can solve this problem.

```php
protected function createMapping()
{
    return new Merge(new FooMapping, new BarMapping);
}
```

Strategies
----------

Strategies are invokable classes that are invoked by Mapper and substituted for their return values. Strategies can be broadly broken down into two categories: fetchers and augmenters. Fetch strategies retrieve data while augmenters change data provided by other strategies.

Strategies are basic building blocks from which complex data manipulation chains can be constructed to meet the bespoke requirements of an application. The composition of strategies forms a powerful object composition DSL that allows us to express how to retrieve and augment data to mould it into the desired format.

For a complete list of strategies please see the [strategy reference](#strategy-reference).

### Writing strategies

Strategies must implement the `Strategy` interface but it is common to extend `Delegate` or `Decorator` because we usually write augmenters which expect another strategy injected into them to provide data. `Delegate` and `Decorator` provide the `delegate()` method, which allows a strategy to evaluate an expression using Mapper, and is usually needed to evaluate the injected strategy. `Delegate` can delegate any expression to Mapper whereas `Decorator` only accepts `Strategy` objects.

It is recommended to name custom strategies with a *Strategy* suffix to help distinguish them from stock strategies.

## Practical example

Suppose we receive two different postal address formats from two different third-party providers. The first provider, FooBook, provides a single UK addresses. The second provider, BarBucket, provides a collection of US addresses. We are tasked with converting both types to the same uniform address format for our application using mappings.

The address format for our application must be a flat array with the following fields.

* line1
* line2 (if applicable)
* city
* postcode
* country

### FooBook address mapping

A sample of the data we receive from FooBook is shown below.

```php
$fooBookAddress = [
    'address' => [
        'name' => 'Mr A Smith',
        'address_line1' => '3 High Street',
        'address_line2' => 'Hedge End',
        'city' => 'SOUTHAMPTON',
        'post_code' => 'SO31 4NG',
    ],
    'country' => 'UK',
];
```

Before continuing, consider attempting to create the mapping on your own, consulting the [reference](#strategy-reference) if unsure which strategies to use. The following code shows how we can create a mapping to convert this address format to our application's format.

```php
class FooBookAddressToAddresesMapping extends Mapping
{
    protected function createMapping()
    {
        return [
            'line1' => new Copy('address->address_line1'),
            'line2' => new Copy('address->address_line2'),
            'city' => new Copy('address->city'),
            'postcode' => new Copy('address->post_code'),
            'country' => new Copy('country'),
        ];
    }
}
```

Since the input data already has the values we want we only need to effectively rename the fields using `Copy` strategies. We do not need the name field so it is left unmapped.

The result of mapping the input data is shown below.

```php
$address = (new Mapper)->map($fooBookAddress, new FooBookAddressToAddresesMapping);

// Output.
[  
    'line1' => '3 High Street',
    'line2' => 'Hedge End',
    'city' => 'SOUTHAMPTON',
    'postcode' => 'SO31 4NG',
    'country' => 'UK',
]
```

### BarBucket address mapping

A sample of the data we receive from BarBucket is show below.

```php
$barBucketAddress = [
    'Addresses' => [
        [
            'Jeremy Martinson, Jr.',
            '455 Larkspur Dr.',
            'Baviera, CA 92908',
        ],
    ],
];
```

This format is a lot less similar to our application's format. In particular, BarBucket's format supports multiple addresses but we're only interested in mapping one so we'll assume the first will suffice and discard any others. Their format also omits the country but we know BarBucket only supplies US addresses so we can assume the country is always "US". Once again, consider attempting to create the mapping on your own before observing the solution below.

```php
class BarBucketAddressToAddresesMapping extends Mapping
{
    protected function createMapping()
    {
        return [
            'line1' => new Copy('Addresses->0->1'),
            'city' => new Callback(
                function (array $data) {
                    return $this->extractCity($data['Addresses'][0][2]);
                }
            ),
            'postcode' => new Callback(
                function (array $data) {
                    return $this->extractZipCode($data['Addresses'][0][2]);
                }
            ),
            'country' => 'US',
        ];
    }

    private function extractCity($line)
    {
        return explode(',', $line, 2)[0];
    }

    private function extractZipCode($line)
    {
        if (preg_match('[.*\b(\d{5})]', $line, $matches)) {
            return $matches[1];
        }
    }
}
```

*Line1* can be copied straight from the input data and *country* can be hard-coded with a constant value because we assume it does not change.

City and postcode must be extracted from the last line of the address. For this we use `Callback` strategies that indirectly point to private methods of our mapping. Callbacks are only necessary because there are currently no included strategies to perform string splitting or regular expression matching.

The anonymous function wrapper picks the relevant part of the input data to pass to our methods. The weakness of this solution is dereferencing non-existent values will cause PHP to generate *undefined index* notices whereas injecting `Copy` strategies would gracefully resolve to `null` if any part of the path does not exist. Therefore, the most elegant solution would be to create custom strategies to promote code reuse and avoid errors, but is beyond the scope of this demonstration. For more information see [writing strategies](#writing-strategies).

The result of mapping the input data is shown below.

```php
$address = (new Mapper)->map($barBucketAddress, new BarBucketAddressToAddresesMapping);

// Output.
[
    'line1' => '455 Larkspur Dr.',
    'city' => 'Baviera',
    'postcode' => '92908',
    'country' => 'US',
],
```

Note that *line2* is not included in our output because it is was declared optional in the requirements. If it was required we could simply add `'line2' => null,` to our mapping, to hard-code its value to `null`, since it is never present in the input data from this provider.

Strategy reference
------------------

The following strategies ship with Mapper and provide a suite of commonly used features, as listed below.

### Strategy index

#### Fetchers

 - [Copy](#copy) &ndash; Copies a portion of input data.
 - [CopyContext](#copycontext) &ndash; Copies a portion of context data.
 - [CopyKey](#copykey) &ndash; Copies the current key.

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
 - [TryCatch](#trycatch) &ndash; Tries the primary strategy and falls back to an expression if an exception is thrown.
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

### CopyKey

Copies the current key from the key context. This strategy requires the key context to be set by another strategy. By default the key context is `null`. Currently only the [collection strategy](#collection) sets a key context.

#### Signature

```php
CopyKey()
```

#### Example

```php
(new Mapper)->map(
    [
        'foo' => [
            'bar' => 'baz',
        ],
    ],
    new Collection(
        new Copy('foo'),
        new CopyKey
    )
)
```

> ['bar' => 'bar']

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

For each item in the collection, this strategy sets the context to the current datum and the key context to the current key, which can be retrieved using [CopyKey](#copykey).

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
 2. `$callback` &ndash; Callback function that receives the current value as its first argument, the current key as its second argument and context as its third argument.

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

### TryCatch

Tries the primary strategy and falls back to an expression if an exception is thrown. The thrown exception is passed to the specified exception handler. The handler should throw an exception if it does not expect the exception type it receives.

Different fallback expressions can be used for different exception types by nesting multiple instances of this strategy.

#### Signature

```php
TryCatch(Strategy $strategy, callable $handler, Strategy|Mapping|array|mixed $expression)
```

 1. `$strategy` &ndash; Primary strategy.
 2. `$handler` &ndash; Exception handler that receives the thrown exception as its first argument.
 3. `$expression` &ndash; Fallback expression.

#### Examples

```php
(new Mapper)->map(
    ['foo' => 'bar'],
    new TryCatch(
        new Callback(
            function () {
                throw new \DomainException;
            }
        ),
        function (\Exception $exception) {
            if (!$exception instanceof \DomainException) {
                throw $exception;
            }
        },
        new Copy('foo')
    )
);
```

> 'bar'

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

Requirements
------------

 - [PHP 5.5](http://php.net/)
 - [Composer](https://getcomposer.org/)

Limitations
-----------

 - Strategies do not know the name of the key they are assigned to because `Mapper` does not forward the key name.
 - Strategies do not know where they sit in a `Mapping` and therefore cannot traverse a mapping relative to their position.
 - The `Collection` strategy overwrites context making any previous context inaccessible to descendants.

Testing
-------

Mapper is fully unit tested. Run the tests with the `composer test` command. All examples
in this document can be found in `DocumentationTest`.


  [Releases]: https://github.com/ScriptFUSION/Mapper/releases
  [Version image]: https://poser.pugx.org/scriptfusion/mapper/version "Latest version"
  [Downloads]: https://packagist.org/packages/scriptfusion/mapper
  [Downloads image]: https://poser.pugx.org/scriptfusion/mapper/downloads "Total downloads"
  [Build]: http://travis-ci.org/ScriptFUSION/Mapper
  [Build image]: https://travis-ci.org/ScriptFUSION/Mapper.svg?branch=master "Build status"
  [Coverage]: https://coveralls.io/github/ScriptFUSION/Mapper
  [Coverage image]: https://coveralls.io/repos/ScriptFUSION/Mapper/badge.svg "Test coverage"
  [Style]: https://styleci.io/repos/59734709
  [Style image]: https://styleci.io/repos/59734709/shield?style=flat "Code style"
