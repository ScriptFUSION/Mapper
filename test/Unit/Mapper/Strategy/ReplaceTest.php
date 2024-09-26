<?php
namespace ScriptFUSIONTest\Unit\Mapper\Strategy;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use ScriptFUSION\Mapper\Expression;
use ScriptFUSION\Mapper\Strategy\Replace;
use ScriptFUSIONTest\MockFactory;

final class ReplaceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @dataProvider provideReplacements
     */
    public function testReplace($input, $search, $replace, $output)
    {
        $replace = (new Replace($input, $search, $replace))->setMapper(MockFactory::mockMapper($input));

        self::assertSame($output, $replace([]));
    }

    public static function provideReplacements()
    {
        return [
            'Single removal' => ['foo', 'o', null, 'f'],
            'Substring removal' => ['foo', 'oo', null, 'f'],

            'Single replacement' => ['foo', 'f', 'b', 'boo'],
            'Substring replacement' => ['foo', 'foo', 'bar', 'bar'],

            'Multiple removal' => ['foo', ['f', 'o'], null, ''],
            'Multiple replacement' => ['foo', ['f', 'o'], ['h', 'a'], 'haa'],

            'Insufficient replacements (uniform types)' => ['foo', ['f', 'o'], ['x'], 'xxx'],
            'Insufficient replacements (mixed types)' => ['foo', ['f', 'o'], 'x', 'xxx'],
            'Extra replacements (uniform types)' => ['foo', ['f'], ['b', 'w'], 'boo'],
            'Extra replacements (mixed types)' => ['foo', 'f', ['b', 'w'], 'boo'],

            'Recursive replacement' => ['foo', ['f', 'b'], ['b', 'w'], 'woo'],

            'Regex single removal' => ['foo', new Expression('[o]'), null, 'f'],
            'Regex substring removal' => ['foo', new Expression('[oo]'), null, 'f'],

            'Regex single replacement' => ['foo', new Expression('[o$]'), 'x', 'fox'],
            'Regex substring replacement' => ['foo', new Expression('[^foo$]'), 'bar', 'bar'],

            'Regex multiple removal' => ['foo', [new Expression('[f]'), new Expression('[o]')], null, ''],
            'Regex multiple replacement' => ['foo', [new Expression('[f]'), new Expression('[o]')], ['h', 'a'], 'haa'],

            'Regex insufficient replacements' => ['foo', [new Expression('[f]'), new Expression('[o]')], ['x'], 'xxx'],
            'Regex extra replacements' => ['foo', new Expression('[f]'), ['b', 'w'], 'boo'],

            'Mixed mode replacement' => ['foo', ['f', new Expression('[o$]')], ['c', 'x'], 'cox'],
            'Sub-match replacement' => ['foo', new Expression('[(f)oo]'), 'o$1$1', 'off'],
        ];
    }
}
