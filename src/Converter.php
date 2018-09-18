<?php declare(strict_types=1);

namespace mohmann\ElasticsearchSynonyms;

use mohmann\ElasticsearchSynonyms\Collection\MappingCollection;
use mohmann\ElasticsearchSynonyms\Formatter\MappingFormatter;
use mohmann\ElasticsearchSynonyms\Formatter\MappingFormatterInterface;
use mohmann\ElasticsearchSynonyms\Mapping\MappingInterface;

class Converter
{
    /**
     * @var MappingFormatterInterface
     */
    private $formatter;

    /**
     * @param MappingFormatterInterface $formatter
     */
    public function __construct(MappingFormatterInterface $formatter = null)
    {
        $this->formatter = $formatter ?? new MappingFormatter();
    }

    /**
     * @param MappingCollection $collection
     * @return array
     */
    public function convertToArray(MappingCollection $collection): array
    {
        $result = [];

        /** @var MappingInterface $mapping */
        foreach ($collection as $mapping) {
            $result[] = $this->formatter->format($mapping);
        }

        return $result;
    }
}
