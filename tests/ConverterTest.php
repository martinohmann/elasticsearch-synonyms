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

use mohmann\ElasticsearchSynonyms\Collection\MappingCollection;
use mohmann\ElasticsearchSynonyms\Collection\SynonymCollection;
use mohmann\ElasticsearchSynonyms\Converter;
use mohmann\ElasticsearchSynonyms\Mapping\Mapping;
use mohmann\ElasticsearchSynonyms\Synonym;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    /**
     * @var Converter
     */
    private $converter;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->converter = new Converter();
    }

    /**
     * @test
     */
    public function itConvertsMappingCollectionToArray()
    {
        $collection = new MappingCollection([
            new Mapping(
                [new Synonym('foo'), new Synonym('bar')],
                [new Synonym('baz')]
            ),
            new Mapping([new Synonym('one'), new Synonym('two')]),
        ]);

        $result = $this->converter->convertToArray($collection);

        $this->assertSame(
            [
                'foo, bar => baz',
                'one, two',
            ],
            $result
        );
    }
}
