<?php
/*
 * This file is part of the elasticsearch-synonyms package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\ElasticsearchSynonyms\Tests;

use PHPUnit\Framework\TestCase;
use mohmann\ElasticsearchSynonyms\Lexer;

class LexerTest extends TestCase
{
    /**
     * @var Lexer
     */
    private $lexer;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->lexer = new Lexer();
    }

    /**
     * @test
     * @dataProvider provideValidTestData
     */
    public function itTokenizesValidInput(string $input, array $expectedTokenValues)
    {
        $expectedCount = \count($expectedTokenValues);

        $stream = $this->lexer->tokenize($input);
        $tokens = $stream->getAll();

        $this->assertCount($expectedCount, $tokens);

        for ($i = 0; $i < $expectedCount; $i++) {
            $this->assertSame($expectedTokenValues[$i], $tokens[$i]->getValue());
        }
    }

    /**
     * @return array
     */
    public function provideValidTestData(): array
    {
        return [
            [
                '# this is a comment',
                ['#', 'this', 'is', 'a', 'comment'],
            ],
            [
                'foo, bar => baz',
                ['foo', ',', 'bar', '=>', 'baz'],
            ],
            [
                'foo,bar=>baz',
                ['foo', ',', 'bar', '=>', 'baz'],
            ],
            [
                'foo, bar => baz# this is a comment',
                ['foo', ',', 'bar', '=>', 'baz', '#', 'this', 'is', 'a', 'comment'],
            ],
            [
                "foo, bar => baz\none two",
                ['foo', ',', 'bar', '=>', 'baz', "\n", 'one', 'two'],
            ],
            [
                "t-shirt tshirt",
                ['t-shirt', 'tshirt'],
            ],
            [
                "saege säge",
                ['saege', 'säge'],
            ],
            [
                "Vêtements, Tøj",
                ['Vêtements', ',', 'Tøj'],
            ],
            [
                'isn\'t, is not',
                ['isn\'t', ',', 'is', 'not'],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideInvalidTestData
     */
    public function itThrowsExceptionOnInvalidInput(string $input, string $exceptionMessage)
    {
        $this->expectExceptionMessage($exceptionMessage);
        $this->lexer->tokenize($input);
    }

    /**
     * @return array
     */
    public function provideInvalidTestData(): array
    {
        return [
            'invalid characters, one line' => [
                'some%, ^words with \\invalid, characters',
                'Lexer error: unable to parse "some%, ^words with \\invalid, characters" at line 1.'
            ],
            'multiline, valid and invalid lines' => [
                "foo,bar=>baz\nin\\/alid, character$",
                'Lexer error: unable to parse "in\\/alid, character$" at line 2.'
            ],
        ];
    }
}
