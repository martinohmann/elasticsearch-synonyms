<?php

namespace mohmann\ElasticsearchSynonyms\Tests;

use PHPUnit\Framework\TestCase;
use mohmann\ElasticsearchSynonyms\Collection\MappingCollection;
use mohmann\ElasticsearchSynonyms\Collection\SynonymCollection;
use mohmann\ElasticsearchSynonyms\Converter;
use mohmann\ElasticsearchSynonyms\Mapping\Mapping;
use mohmann\ElasticsearchSynonyms\Synonym;

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
