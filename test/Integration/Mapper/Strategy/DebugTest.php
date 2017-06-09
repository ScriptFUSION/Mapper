<?php
namespace ScriptFUSIONTest\Integration\Mapper\Strategy {

    use ScriptFUSION\Mapper\Mapper;
    use ScriptFUSION\Mapper\Strategy\Copy;
    use ScriptFUSION\Mapper\Strategy\Debug;

    final class DebugTest extends \PHPUnit_Framework_TestCase
    {
        public static $debugged;

        protected function setUp()
        {
            self::$debugged = false;
        }

        /**
         * Tests that the strategy can be created with no arguments.
         */
        public function testNoArguments()
        {
            new Debug;
        }

        /**
         * Tests that expressions are delegated to Mapper.
         */
        public function testDelegation()
        {
            $debug = (new Debug(new Copy(0)))->setMapper(new Mapper);

            self::assertSame($record = 'foo', $debug([$record]));
        }

        /**
         * Tests that the Xdebug breakpoint is called.
         */
        public function testXdebug()
        {
            $debug = (new Debug)->setMapper(new Mapper);

            $debug([]);

            self::assertTrue(self::$debugged);
        }
    }
}

// Mock debugging functions.
namespace ScriptFUSION\Mapper\Strategy {

    use ScriptFUSIONTest\Integration\Mapper\Strategy\DebugTest;

    function xdebug_break()
    {
        DebugTest::$debugged = true;
    }
}
