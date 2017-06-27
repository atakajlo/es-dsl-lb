<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Aggregation\Metrics;

use CodeTool\ElasticSearch\DSL\Aggregation\ElasticSearchAggregationInterface;

/**
 * A single-value metrics aggregation that counts the number of values
 * that are extracted from the aggregated documents.
 * These values can be extracted either from specific fields in the documents,
 * or be generated by a provided script.
 * Typically, this aggregator will be used in conjunction with other single-value aggregations.
 * For example, when computing the avg one might be interested in the number of values the average is computed over.
 * @see: http://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-valuecount-aggregation.html
 */
class ElasticSearchAggregationMetricsValueCount implements ElasticSearchAggregationInterface
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var  string
     */
    private $format;

    /**
     * @var ElasticSearchAggregationInterface[]
     */
    private $subAggregations = [];

    /**
     * @var string[]
     */
    private $meta = [];

    public function field(string $field)
    {
        $this->field = $field;

        return $this;
    }

    public function format(string $format)
    {
        $this->format = $format;

        return $this;
    }

    public function subAggregation(string $name, ElasticSearchAggregationInterface $subAggregation)
    {
        $this->subAggregations[$name] = $subAggregation;

        return $this;
    }

    public function meta(array $metaData)
    {
        $this->meta = $metaData;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $options = [];
        if ('' !== $this->field) {
            $options['field'] = $this->field;
        }

        if ('' !== $this->format && null !== $this->format) {
            $options['format'] = $this->format;
        }

        $result = ['value_count' => $options];

        if (0 !== count($this->subAggregations)) {
            $result['aggregations'] = array_map(
                function (ElasticSearchAggregationInterface $searchAggregation) {
                    return $searchAggregation->jsonSerialize();
                },
                $this->subAggregations
            );
        }

        if (0 !== count($this->meta)) {
            $result['meta'] = $this->meta;
        }

        return $result;
    }
}
