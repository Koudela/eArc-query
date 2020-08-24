<?php declare(strict_types=1);
/**
 * e-Arc Framework - the explicit Architecture Framework
 *
 * @package earc/query-language
 * @link https://github.com/Koudela/eArc-query-language/
 * @copyright Copyright (c) 2020 Thomas Koudela
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace eArc\QueryLanguage\Result;

use eArc\QueryLanguage\Interfaces\ResolverInterface;
use IteratorAggregate;

class QueryResult implements IteratorAggregate
{
    /** @var ResolverInterface */
    protected $resolver;
    /** @var string[] */
    protected $allowedDataIdentifiers;
    /** @var string */
    protected $dataCategory;
    /** @var iterable */
    protected $items;
    /** @var int */
    protected $limit;
    /** @var int */
    protected $offset;

    public function __construct(
        ResolverInterface $resolver,
        string $dataCategory,
        ?iterable $items,
        ?iterable $allowedDataIdentifiers,
        int $limit = 0,
        int $offset = 0,
        ?string $sort = null,
        ?array $sortBy = null
    )
    {
        $this->resolver = $resolver;
        $this->allowedDataIdentifiers = $allowedDataIdentifiers;
        $this->dataCategory = $dataCategory;
        if (is_null($sort)) {
            $this->items = $items ?? $resolver->findAll($dataCategory);
        } else {
            $this->items = $this->resolver
                ->sort($dataCategory, $sort, $sortBy, $items, $allowedDataIdentifiers, $limit, $offset);
        }
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function getIterator()
    {
        $allowedDataIdentifiers = is_array($this->allowedDataIdentifiers) ? array_flip($this->allowedDataIdentifiers) : null;

        $cnt = -1;
        foreach ($this->items as $dataIdentifier => $item) {
            if (!is_null($allowedDataIdentifiers) && !array_key_exists($dataIdentifier, $allowedDataIdentifiers)) {
                continue;
            }
            $cnt++;
            if ($this->offset !== 0 && $cnt < $this->offset) {
                continue;
            }
            if ($this->limit !== 0 && $cnt >= $this->offset + $this->limit) {
                break;
            }

            yield $dataIdentifier => $item;
        }
    }
}
