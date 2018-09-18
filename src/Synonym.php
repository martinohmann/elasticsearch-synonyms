<?php declare(strict_types=1);
/*
 * This file is part of the elasticsearch-synonyms package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\ElasticsearchSynonyms;

class Synonym
{
    /**
     * @var array
     */
    private $words;

    /**
     * @param mixed $words
     */
    public function __construct($words = [])
    {
        $this->setWords($words);
    }

    /**
     * @param string $word
     * @return Synonym
     */
    public function addWord(string $word): Synonym
    {
        $this->words[] = $word;

        return $this;
    }

    /**
     * @return array
     */
    public function getWords(): array
    {
        return $this->words;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return \trim(\implode(' ', $this->words));
    }

    /**
     * @param mixed $words
     * @return void
     */
    private function setWords($words): void
    {
        if (\is_object($words)) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Primitive type expected, object of type "%s" found',
                    \get_class($words)
                )
            );
        }

        $this->words = \is_array($words) ? \array_map('strval', $words) : [(string) $words];
    }
}
