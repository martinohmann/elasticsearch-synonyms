<?php
/*
 * This file is part of the elasticsearch-synonyms package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\ElasticsearchSynonyms\Tests\Formatter;

use mohmann\ElasticsearchSynonyms\Collection\SynonymCollection;
use mohmann\ElasticsearchSynonyms\Formatter\MappingFormatter;
use mohmann\ElasticsearchSynonyms\Mapping\MappingInterface;
use mohmann\ElasticsearchSynonyms\Synonym;
use PHPUnit\Framework\TestCase;

class MappingFormatterTest extends TestCase
{
    /**
     * @var MappingInterface
     */
    private $mapping;

    /**
     * @var MappingFormatter
     */
    private $formatter;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->mapping = \Phake::mock(MappingInterface::class);
        $this->formatter = new MappingFormatter();
    }

    /**
     * @test
     * @dataProvider provideTestData
     */
    public function itProducesCorrectStringRepresentation(array $synonyms, array $replacements, string $expected)
    {
        \Phake::when($this->mapping)
            ->getSynonyms()
            ->thenReturn(new SynonymCollection($synonyms));

        \Phake::when($this->mapping)
            ->getReplacements()
            ->thenReturn(new SynonymCollection($replacements));

        $this->assertSame($expected, $this->formatter->format($this->mapping));
    }

    /**
     * @return array
     */
    public function provideTestData(): array
    {
        return [
            [
                [],
                [],
                '',
            ],
            [
                [new Synonym(['foo', 'bar'])],
                [],
                'foo bar',
            ],
            [
                [new Synonym('foo bar'), new Synonym('baz')],
                [],
                'foo bar, baz',
            ],
            [
                [],
                [new Synonym('foo bar')],
                'foo bar',
            ],
            [
                [],
                [new Synonym('foo bar'), new Synonym('baz')],
                'foo bar, baz',
            ],
            [
                [new Synonym('foo')],
                [new Synonym('bar'), new Synonym('baz')],
                'foo => bar, baz',
            ],
        ];
    }
}
