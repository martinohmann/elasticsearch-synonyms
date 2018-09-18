<?php
/*
 * This file is part of the elasticsearch-synonyms package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\ElasticsearchSynonyms\Tests\Collection;

use mohmann\ElasticsearchSynonyms\Collection\AbstractTypeSafeCollection;
use mohmann\ElasticsearchSynonyms\Tests\Collection\DummyCollection;
use PHPUnit\Framework\TestCase;

class AbstractTypeSafeCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function itKnowsElementFqcn()
    {
        $collection = new DummyCollection();

        $this->assertSame(\stdClass::class, $collection->getElementFqcn());
    }

    /**
     * @test
     */
    public function itConstructsWithElements()
    {
        $element = new \stdClass;
        $element->foo = 'bar';

        $collection = new DummyCollection([$element]);

        $this->assertCount(1, $collection);
        $this->assertSame($element, $collection->toArray()[0]);
    }

    /**
     * @test
     */
    public function itAddsElements()
    {
        $collection = new DummyCollection();

        $this->assertTrue($collection->isEmpty());

        $element = new \stdClass;
        $element->foo = 'bar';

        $collection->add($element);

        $this->assertCount(1, $collection);
        $this->assertSame($element, $collection->toArray()[0]);
    }

    /**
     * @test
     */
    public function itThrowsExceptionWhenConstructedWithElementsOfInvalidType()
    {
        $this->expectException(\InvalidArgumentException::class);
        new DummyCollection([new \stdClass, 'foo']);
    }

    /**
     * @test
     */
    public function itThrowsExceptionWhenAddingElementOfInvalidType()
    {
        $collection = new DummyCollection();

        $this->expectException(\InvalidArgumentException::class);
        $collection->add(new \ArrayIterator([]));
    }
}
