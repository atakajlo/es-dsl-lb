<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

/**
 *  FuzzyQuery uses similarity based on Levenshtein edit distance for
 *  string fields, and a +/- margin on numeric and date fields.
 *
 * For more details, @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-fuzzy-query.html
 */
final class ElasticSearchQueryFuzzy implements ElasticSearchDSLQueryInterface
{
    private string $name;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string|int
     */
    private $fuzziness;

    private ?int $prefixLength;

    private ?int $maxExpansions;

    private ?bool $transpositions;

    private string $rewrite = '';

    private ?float $boost;

    private string $queryName = '';

    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function boost(float $boost): self
    {
        $this->boost = $boost;

        return $this;
    }

    /**
     * @param int|string $fuzziness can be an integer/long like 0, 1 or 2 as well as strings
     *                              like "auto", "0..1", "1..4" or "0.0..1.0".
     *
     * @return $this
     */
    public function fuzziness($fuzziness): self
    {
        $this->fuzziness = $fuzziness;

        return $this;
    }

    public function prefixLength(int $prefixLength): self
    {
        $this->prefixLength = $prefixLength;

        return $this;
    }

    public function maxExpansions(int $maxExpansions): self
    {
        $this->maxExpansions = $maxExpansions;

        return $this;
    }

    public function transpositions(bool $transpositions): self
    {
        $this->transpositions = $transpositions;

        return $this;
    }

    public function rewrite(string $rewrite): self
    {
        $this->rewrite = $rewrite;

        return $this;
    }

    public function queryName(string $queryName): self
    {
        $this->queryName = $queryName;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $query = ['value' => $this->value];

        if (null !== $this->boost) {
            $query['boost'] = $this->boost;
        }

        if (null !== $this->transpositions) {
            $query['transpositions'] = $this->transpositions;
        }

        if (null !== $this->fuzziness) {
            $query['fuzziness'] = $this->fuzziness;
        }

        if (null !== $this->prefixLength) {
            $query['prefix_length'] = $this->prefixLength;
        }

        if (null !== $this->maxExpansions) {
            $query['max_expansions'] = $this->maxExpansions;
        }

        if ('' !== $this->rewrite) {
            $query['rewrite'] = $this->rewrite;
        }

        if ('' !== $this->queryName) {
            $query['_name'] = $this->queryName;
        }

        return ['fuzzy' => [$this->name => $query]];
    }
}
