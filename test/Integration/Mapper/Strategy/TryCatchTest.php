<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use ScriptFUSION\Mapper\Mapper;
use ScriptFUSION\Mapper\Strategy\Callback;
use ScriptFUSION\Mapper\Strategy\TryCatch;

final class TryCatchTest extends \PHPUnit_Framework_TestCase
{
    private $callback;

    protected function setUp()
    {
        $this->callback = new Callback(function ($data) {
            if ($data[0] instanceof \Exception) {
                throw $data[0];
            }

            return $data;
        });
    }

    public function testTryCatch()
    {
        /** @var TryCatch $tryCatch */
        $tryCatch = (
            new TryCatch(
                $this->callback,
                function (\Exception $e) {
                    if (!$e instanceof \DomainException) {
                        throw $e;
                    }
                },
                $fallback = 'bar'
            )
        )->setMapper(new Mapper);

        self::assertSame($data = ['foo'], $tryCatch($data));
        self::assertSame($fallback, $tryCatch([new \DomainException]));

        $this->setExpectedException(\RuntimeException::class);
        $tryCatch([new \RuntimeException]);
    }

    public function testNestedTryCatch()
    {
        /** @var TryCatch $tryCatch */
        $tryCatch = (
            new TryCatch(
                new TryCatch(
                    $this->callback,
                    function (\Exception $e) {
                        if (!$e instanceof \DomainException) {
                            throw $e;
                        }
                    },
                    $innerFallback = 'bar'
                ),
                function (\Exception $e) {
                    if (!$e instanceof \LogicException) {
                        throw $e;
                    }
                },
                $outerFallback = 'baz'
            )
        )->setMapper(new Mapper);

        self::assertSame($data = ['foo'], $tryCatch($data));
        self::assertSame($innerFallback, $tryCatch([new \DomainException]));
        self::assertSame($outerFallback, $tryCatch([new \LogicException]));

        $this->setExpectedException(\RuntimeException::class);
        $tryCatch([new \RuntimeException]);
    }
}
