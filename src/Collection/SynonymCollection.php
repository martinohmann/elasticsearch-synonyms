<?php declare(strict_types=1);

namespace mohmann\ElasticsearchSynonyms\Collection;

use mohmann\ElasticsearchSynonyms\Synonym;

class SynonymCollection extends AbstractTypeSafeCollection
{
    /**
     * {@inheritDoc}
     */
    public function getElementFqcn(): string
    {
        return Synonym::class;
    }
}
