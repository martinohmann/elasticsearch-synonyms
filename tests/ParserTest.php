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
use mohmann\ElasticsearchSynonyms\Parser;
use mohmann\ElasticsearchSynonyms\Lexer;
use mohmann\ElasticsearchSynonyms\Converter;
use Yosymfony\ParserUtils\SyntaxErrorException;

class ParserTest extends TestCase
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Converter
     */
    private $converter;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->parser = new Parser(new Lexer());
        $this->converter = new Converter();
    }

    /**
     * @test
     * @dataProvider provideValidInputData
     */
    public function itParsesValidInputs(string $input, array $expected)
    {
        $collection = $this->parser->parse($input);

        $this->assertSame($expected, $this->converter->convertToArray($collection));
    }

    /**
     * @return array
     */
    public function provideValidInputData(): array
    {
        return [
            'empty input' => [
                '',
                [],
            ],
            'single line, lhs' => [
                'foo,bar',
                [
                    'foo, bar',
                ],
            ],
            'single line, lhs are rhs' => [
                'foo,bar=>baz',
                [
                    'foo, bar => baz',
                ],
            ],
            'single line, lhs are rhs, comment' => [
                'foo,bar=>baz # this is a comment',
                [
                    'foo, bar => baz',
                ],
            ],
            'multiline, first line empty' => [
                "\nfoo,bar=>baz \n# this is a comment\nbar\nfoo =>bar",
                [
                    'foo, bar => baz',
                    'bar',
                    'foo => bar',
                ],
            ],
            'multiline, last line empty' => [
                "foo,bar=>baz \n# this is a comment\nbar\nfoo =>bar\n",
                [
                    'foo, bar => baz',
                    'bar',
                    'foo => bar',
                ],
            ],
            'multiline' => [
                "\n# foo,bar=>baz \n\nbaz=>bar, foo\nthis line ,has a comment # this is a comment\nmultiple words, one, word",
                [
                    'baz => bar, foo',
                    'this line, has a comment',
                    'multiple words, one, word',
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideMalformedInputData
     */
    public function itThrowsExceptionOnMalformedInputs(string $input, string $exceptionMessage)
    {
        $this->expectExceptionMessage($exceptionMessage);
        $this->parser->parse($input);
    }

    /**
     * @return array
     */
    public function provideMalformedInputData(): array
    {
        return [
            'comma at line start' => [
                ' ,foo,bar',
                \sprintf(
                    '"%s" has to be preceeded by "%s"',
                    Lexer::T_COMMA,
                    Lexer::T_WORD
                )
            ],
            'comma at line end' => [
                ' foo,bar,',
                \sprintf(
                    '"%s" has to be followed by "%s"',
                    Lexer::T_COMMA,
                    Lexer::T_WORD
                )
            ],
            'multiple commas in a row' => [
                'foo, ,bar',
                \sprintf(
                    '"%s" has to be followed by "%s"',
                    Lexer::T_COMMA,
                    Lexer::T_WORD
                )
            ],
            'arrow at line start' => [
                '=>foo',
                \sprintf(
                    '"%s" has to be preceeded by "%s"',
                    Lexer::T_ARROW,
                    Lexer::T_WORD
                )
            ],
            'arrow at line end' => [
                'foo =>',
                \sprintf(
                    '"%s" has to be followed by "%s"',
                    Lexer::T_ARROW,
                    Lexer::T_WORD
                )
            ],
            'multiple arrows in one line' => [
                'foo => bar => baz',
                \sprintf(
                    '"%s" is only allowed once per line',
                    Lexer::T_ARROW
                )
            ],
            'multiple arrows in a row' => [
                'foo =>=> baz',
                \sprintf(
                    '"%s" has to be followed by "%s"',
                    Lexer::T_ARROW,
                    Lexer::T_WORD
                )
            ],
        ];
    }
}
