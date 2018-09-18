<?php declare(strict_types=1);

namespace mohmann\ElasticsearchSynonyms\Formatter;

use mohmann\ElasticsearchSynonyms\Mapping\MappingInterface;
use mohmann\ElasticsearchSynonyms\Collection\SynonymCollection;

class MappingFormatter implements MappingFormatterInterface
{
    /**
     * {@inheritDoc}
     */
    public function format(MappingInterface $mapping): string
    {
        $result = [];

        /** @var SynonymCollection $synonyms */
        foreach ([$mapping->getSynonyms(), $mapping->getReplacements()] as $synonyms) {
            if ($synonyms->isEmpty()) {
                continue;
            }

            $result[] = \implode(', ', $synonyms->toArray());
        }

        return \implode(' => ', $result);
    }
}
