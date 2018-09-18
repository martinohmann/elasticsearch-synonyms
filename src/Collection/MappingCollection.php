<?php declare(strict_types=1);

namespace mohmann\ElasticsearchSynonyms\Collection;

use mohmann\ElasticsearchSynonyms\Mapping\Mapping;

class MappingCollection extends AbstractTypeSafeCollection
{
    /**
     * {@inheritDoc}
     */
    public function getElementFqcn(): string
    {
        return Mapping::class;
    }
}
