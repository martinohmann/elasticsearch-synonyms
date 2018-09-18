<?php declare(strict_types=1);

namespace mohmann\ElasticsearchSynonyms\Mapping;

use mohmann\ElasticsearchSynonyms\Synonym;
use mohmann\ElasticsearchSynonyms\Collection\SynonymCollection;

interface MappingInterface
{
    /**
     * @param Synonym $synonym
     * @return MappingInterface
     */
    public function addSynonym(Synonym $synonym): MappingInterface;

    /**
     * @return SynonymCollection
     */
    public function getSynonyms(): SynonymCollection;

    /**
     * @return bool
     */
    public function hasSynonyms(): bool;

    /**
     * @param Synonym $synonym
     * @return MappingInterface
     */
    public function addReplacement(Synonym $synonym): MappingInterface;

    /**
     * @return SynonymCollection
     */
    public function getReplacements(): SynonymCollection;

    /**
     * @return bool
     */
    public function hasReplacements(): bool;
}
