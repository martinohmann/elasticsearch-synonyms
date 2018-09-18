<?php declare(strict_types=1);

namespace mohmann\ElasticsearchSynonyms;

use mohmann\ElasticsearchSynonyms\Collection\MappingCollection;

interface ParserInterface
{
    /**
     * @param string $input
     * @return MappingCollection
     */
    public function parse(string $input): MappingCollection;
}
