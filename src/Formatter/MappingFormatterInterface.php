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

use mohmann\ElasticsearchSynonyms\Mapping\MappingInterface;

interface MappingFormatterInterface
{
    /**
     * @param MappingInterface $synonymMapping
     * @return void
     */
    public function format(MappingInterface $synonymMapping): string;
}
