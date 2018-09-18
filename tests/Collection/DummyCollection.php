<?php declare(strict_types=1);

namespace mohmann\ElasticsearchSynonyms\Tests\Collection;

use mohmann\ElasticsearchSynonyms\Collection\AbstractTypeSafeCollection;

class DummyCollection extends AbstractTypeSafeCollection
{
    /**
     * {@inheritDoc}
     */
    public function getElementFqcn(): string
    {
        return \stdClass::class;
    }
}
