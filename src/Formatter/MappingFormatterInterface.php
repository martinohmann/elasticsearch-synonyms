<?php declare(strict_types=1);

namespace mohmann\ElasticsearchSynonyms\Formatter;

use mohmann\ElasticsearchSynonyms\Mapping\MappingInterface;

interface MappingFormatterInterface
{
    /**
     * @param MappingInterface $synonymMapping
     * @return void
     */
    public function format(MappingInterface $synonymMapping): string;
}
