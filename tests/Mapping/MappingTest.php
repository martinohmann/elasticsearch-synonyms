<?php
/*
 * This file is part of the elasticsearch-synonyms package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\ElasticsearchSynonyms\Tests\Mapping;

use PHPUnit\Framework\TestCase;
use mohmann\ElasticsearchSynonyms\Mapping\Mapping;
use mohmann\ElasticsearchSynonyms\Synonym;

class MappingTest extends TestCase
{
    /**
     * @var Mapping
     */
    private $mapping;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->mapping = new Mapping();
    }

    /**
     * @test
     */
    public function itAddsSynonyms()
    {
        $synonym = new Synonym('foo');

        $this->assertFalse($this->mapping->hasSynonyms());

        $this->mapping->addSynonym($synonym);

        $synonyms = $this->mapping->getSynonyms();

        $this->assertTrue($this->mapping->hasSynonyms());
        $this->assertCount(1, $synonyms);
        $this->assertSame($synonym, $synonyms->toArray()[0]);
    }

    /**
     * @test
     */
    public function itAddsReplacement()
    {
        $synonym = new Synonym('bar');

        $this->assertFalse($this->mapping->hasReplacements());

        $this->mapping->addReplacement($synonym);

        $replacements = $this->mapping->getReplacements();

        $this->assertTrue($this->mapping->hasReplacements());
        $this->assertCount(1, $replacements);
        $this->assertSame($synonym, $replacements->toArray()[0]);
    }
}
