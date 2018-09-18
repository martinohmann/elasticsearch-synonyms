<?php declare(strict_types=1);

namespace mohmann\ElasticsearchSynonyms\Collection;

abstract class AbstractTypeSafeCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var array
     */
    private $elements = [];

    /**
     * @param array $elements
     */
    public function __construct(array $elements = [])
    {
        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    /**
     * @return string
     */
    abstract public function getElementFqcn(): string;

    /**
     * @param mixed $element
     * @return AbstractTypeSafeCollection
     */
    public function add($element): AbstractTypeSafeCollection
    {
        $this->validateType($element);

        $this->elements[] = $element;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->elements;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return \count($this->elements);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return 0 == $this->count();
    }

    /**
     * @param mixed $element
     * @return void
     */
    private function validateType($element)
    {
        $className = $this->getElementFqcn();

        if (!$element instanceof $className) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Expected element to be of type "%s", got "%s"',
                    $className,
                    \is_object($element) ? \get_class($element) : \gettype($element)
                )
            );
        }
    }
}
