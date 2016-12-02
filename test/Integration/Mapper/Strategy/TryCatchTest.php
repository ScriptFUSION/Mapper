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
            if ($data[0] === 'LogicError') {
                throw new \LogicException('Test Exception');
            }
            if ($data[0] === 'DomainError') {
                throw new \DomainException('Test Exception');
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
                function ($e) {
                },
                'ExceptionHandled'
            )
        )->setMapper(new Mapper);
        self::assertSame(['foo','bar'], $tryCatch(['foo','bar']));
        self::assertSame('ExceptionHandled', $tryCatch(['DomainError','foo']));
    }

    public function testMultipleTryCatch()
    {
        /** @var TryCatch $tryCatch */
        $tryCatch = (
        new TryCatch(
            new TryCatch(
                $this->callback,
                function ($e) {
                    if (get_class($e) === \DomainException::class) {
                        throw $e;
                    }
                },
                'LogicExceptionHandled'
            ),
            function ($e) {
            },
            'DomainExceptionHandled'
        )
        )->setMapper(new Mapper);
        self::assertSame(['foo','bar'], $tryCatch(['foo','bar']));
        self::assertSame('LogicExceptionHandled', $tryCatch(['LogicError','foo']));
        self::assertSame('DomainExceptionHandled', $tryCatch(['DomainError','foo']));
    }
}
