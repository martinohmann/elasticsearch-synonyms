<?php declare(strict_types=1);
/*
 * This file is part of the elasticsearch-synonyms package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\ElasticsearchSynonyms\Formatter;

use mohmann\ElasticsearchSynonyms\Collection\SynonymCollection;
use mohmann\ElasticsearchSynonyms\Mapping\MappingInterface;

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
