<?php declare(strict_types=1);
/*
 * This file is part of the elasticsearch-synonyms package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\ElasticsearchSynonyms\Mapping;

use mohmann\ElasticsearchSynonyms\Synonym;
use mohmann\ElasticsearchSynonyms\Collection\SynonymCollection;

class Mapping implements MappingInterface
{
    /**
     * @var SynonymCollection
     */
    private $synonyms;

    /**
     * @var SynonymCollection
     */
    private $replacements;

    /**
     * @param array $synonyms
     * @param array $replacements
     */
    public function __construct(array $synonyms = [], array $replacements = [])
    {
        $this->synonyms = new SynonymCollection($synonyms);
        $this->replacements = new SynonymCollection($replacements);
    }

    /**
     * {@inheritDoc}
     */
    public function addSynonym(Synonym $synonym): MappingInterface
    {
        $this->synonyms->add($synonym);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSynonyms(): SynonymCollection
    {
        return $this->synonyms;
    }

    /**
     * {@inheritDoc}
     */
    public function hasSynonyms(): bool
    {
        return !$this->synonyms->isEmpty();
    }

    /**
     * {@inheritDoc}
     */
    public function addReplacement(Synonym $synonym): MappingInterface
    {
        $this->replacements->add($synonym);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getReplacements(): SynonymCollection
    {
        return $this->replacements;
    }

    /**
     * {@inheritDoc}
     */
    public function hasReplacements(): bool
    {
        return !$this->replacements->isEmpty();
    }
}
