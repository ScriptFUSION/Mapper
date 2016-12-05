<?php
namespace ScriptFUSIONTest\Functional;

use ScriptFUSION\Mapper\DataType;
use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Callback;
use ScriptFUSION\Mapper\Strategy\Collection;
use ScriptFUSION\Mapper\Strategy\Context;
use ScriptFUSION\Mapper\Strategy\Copy;
use ScriptFUSION\Mapper\Strategy\CopyContext;
use ScriptFUSION\Mapper\Strategy\Either;
use ScriptFUSION\Mapper\Strategy\Filter;
use ScriptFUSION\Mapper\Strategy\Flatten;
use ScriptFUSION\Mapper\Strategy\IfExists;
use ScriptFUSION\Mapper\Strategy\Merge;
use ScriptFUSION\Mapper\Strategy\TakeFirst;
use ScriptFUSION\Mapper\Strategy\ToList;
use ScriptFUSION\Mapper\Strategy\TryCatch;
use ScriptFUSION\Mapper\Strategy\Type;
use ScriptFUSION\Mapper\Strategy\Unique;
use ScriptFUSION\Mapper\Strategy\Walk;
use ScriptFUSIONTest\Fixture\BarBucketAddressToAddresesMapping;
use ScriptFUSIONTest\Fixture\FooBookAddressToAddresesMapping;
use ScriptFUSIONTest\Fixture\FooToBarMapping;

final class DocumentationTest extends \PHPUnit_Framework_TestCase
{
    public function testFooToBarMapping()
    {
        $fooData = ['foo' => 123];
        $barData = (new Mapper)->map($fooData, new FooToBarMapping);

        self::assertSame(['bar' => 123], $barData);
    }

    public function testFooBookAddressToAddresesMapping()
    {
        self::assertSame(
            [
                'line1' => $line1 = '3 High Street',
                'line2' => $line2 = 'Hedge End',
                'city' => $city = 'SOUTHAMPTON',
                'postcode' => $postcode = 'SO31 4NG',
                'country' => $country = 'UK',
            ],
            (new Mapper)->map(
                [
                    'address' => [
                        'name' => 'Mr A Smith',
                        'address_line1' => $line1,
                        'address_line2' => $line2,
                        'city' => $city,
                        'post_code' => $postcode,
                    ],
                    'country' => $country,
                ],
                new FooBookAddressToAddresesMapping
            )
        );
    }

    public function testBarBucketAddressToAddresesMapping()
    {
        self::assertSame(
            [
                'line1' => $line1 = '455 Larkspur Dr.',
                'city' => $city = 'Baviera',
                'postcode' => $postcode = '92908',
                'country' => 'US',
            ],
            (new Mapper)->map(
                [
                    'Addresses' => [
                        [
                            'Jeremy Martinson, Jr.',
                            $line1,
                            "$city, CA $postcode",
                        ],
                    ],
                ],
                new BarBucketAddressToAddresesMapping
            )
        );
    }

    public function testCopy()
    {
        $data = [
            'foo' => $foo = [
                'bar' => $bar = 123,
            ],
        ];

        self::assertSame($foo, (new Mapper)->map($data, new Copy('foo')));
        self::assertSame($bar, (new Mapper)->map($data, new Copy('foo->bar')));
        self::assertSame($bar, (new Mapper)->map($data, new Copy(['foo', 'bar'])));
    }

    public function testCopyContext()
    {
        $data = ['foo' => 123];
        $context = ['foo' => 456];

        self::assertSame(456, (new Mapper)->map($data, new CopyContext('foo'), $context));
    }

    public function testCallback()
    {
        self::assertSame(
            15,
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
            )
        );
    }

    public function testCollection()
    {
        self::assertSame(
            [2, 4, 6, 8, 10],
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
            )
        );
    }

    public function testContext()
    {
        self::assertSame(
            $context = 456,
            (new Mapper)->map(
                ['foo' => 123],
                new Context(
                    new CopyContext('foo'),
                    ['foo' => $context]
                ),
                ['foo' => 789]
            )
        );
    }

    public function testEither()
    {
        self::assertSame(
            $bar = 'bar',
            (new Mapper)->map(
                ['bar' => $bar],
                new Either(new Copy('foo'), new Copy('bar'))
            )
        );
    }

    public function testFilter()
    {
        self::assertSame(
            [1, 3, 5, 7, 9],
            array_values(
                (new Mapper)->map(
                    ['foo' => range(1, 10)],
                    new Filter(
                        new Copy('foo'),
                        function ($value) {
                            return $value % 2;
                        }
                    )
                )
            )
        );
    }

    public function testFlatten()
    {
        $data = [
            'foo' => [
                range(1, 3),
                'bar' => [range(3, 5)],
            ],
        ];

        self::assertSame(range(3, 5), (new Mapper)->map($data, new Flatten(new Copy('foo'))));
        self::assertSame([1, 2, 3, 3, 4, 5], (new Mapper)->map($data, (new Flatten(new Copy('foo')))->ignoreKeys()));
    }

    public function testIfExists()
    {
        $data = ['foo' => 'foo'];

        self::assertTrue((new Mapper)->map($data, new IfExists(new Copy('foo'), true, false)));
        self::assertFalse((new Mapper)->map($data, new IfExists(new Copy('bar'), true, false)));
    }

    public function testMerge()
    {
        self::assertSame(
            [1, 2, 3, 3, 4, 5],
            (new Mapper)->map(
                [
                    'foo' => range(1, 3),
                    'bar' => range(3, 5),
                ],
                new Merge(new Copy('foo'), new Copy('bar'))
            )
        );
    }

    public function testTakeFirst()
    {
        self::assertSame(
            $baz = 123,
            (new Mapper)->map(
                [
                    'foo' => [
                        'bar' => [
                            'baz' => $baz,
                            'quz' => 456,
                        ],
                    ],
                ],
                new TakeFirst(new Copy('foo'), 2)
            )
        );
    }

    public function testToList()
    {
        self::assertSame(
            ['bar'],
            (new Mapper)->map(['foo' => 'bar'], new ToList(new Copy('foo')))
        );
    }

    public function testTryCatch()
    {
        self::assertSame(
            'bar',
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
            )
        );
    }

    public function testType()
    {
        self::assertSame(
            '123',
            (new Mapper)->map(['foo' => 123], new Type(DataType::STRING(), new Copy('foo')))
        );
    }

    public function testUnique()
    {
        self::assertSame(
            range(1, 5),
            array_values(
                (new Mapper)->map(
                    ['foo' => array_merge(range(1, 3), range(3, 5))],
                    new Unique(new Copy('foo'))
                )
            )
        );
    }

    public function testWalk()
    {
        self::assertSame(
            $baz = 123,
            (new Mapper)->map(
                [
                    'foo' => [
                        'bar' => [
                            'baz' => $baz,
                        ],
                    ],
                ],
                new Walk(new Copy('foo'), 'bar->baz')
            )
        );
    }
}
