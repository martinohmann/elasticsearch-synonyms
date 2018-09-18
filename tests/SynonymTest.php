<?php
/*
 * This file is part of the elasticsearch-synonyms package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\ElasticsearchSynonyms\Tests;

use mohmann\ElasticsearchSynonyms\Synonym;
use PHPUnit\Framework\TestCase;

class SynonymTest extends TestCase
{
    /**
     * @test
     */
    public function itAcceptsSingleWords()
    {
        $word = 'foo';
        $synonym = new Synonym($word);

        $this->assertSame([$word], $synonym->getWords());
    }

    /**
     * @test
     */
    public function itAcceptsArraysOfWords()
    {
        $words = ['foo', 'bar'];
        $synonym = new Synonym($words);

        $this->assertSame($words, $synonym->getWords());
    }

    /**
     * @test
     */
    public function itCastsNonStringArgumentsToString()
    {
        $synonym = new Synonym(1);

        $this->assertSame(['1'], $synonym->getWords());
    }

    /**
     * @test
     */
    public function itCastsArrayElementsToString()
    {
        $synonym = new Synonym([1, null, true, false, 2.5]);

        $this->assertSame(['1', '', '1', '', '2.5'], $synonym->getWords());
    }

    /**
     * @test
     */
    public function itThrowsExceptionWhenObjectIsPassed()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Synonym(new \stdClass);
    }
}
