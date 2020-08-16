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

use eArc\QueryLanguage\Interfaces\QueryIndexServiceInterface;
use IteratorAggregate;

class QueryResult implements IteratorAggregate
{
    /** @var QueryIndexServiceInterface */
    protected $queryIndexService;
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
        QueryIndexServiceInterface $queryIndexService,
        ?array $allowedDataIdentifiers,
        string $dataCategory,
        iterable $items,
        int $limit = 0,
        int $offset = 0
    )
    {
        $this->queryIndexService = $queryIndexService;
        $this->allowedDataIdentifiers = $allowedDataIdentifiers;
        $this->dataCategory = $dataCategory;
        $this->items = $items;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function getIterator()
    {
        $allowedDataIdentifiers = is_array($this->allowedDataIdentifiers) ? array_flip($this->allowedDataIdentifiers) : null;

        $cnt = 0;
        foreach ($this->items as $dataIdentifier => $item) {
            if (!is_null($allowedDataIdentifiers) && !array_key_exists($dataIdentifier, $allowedDataIdentifiers)) {
                continue;
            }
            if ($cnt < $this->offset) {
                continue;
            }
            if (0 !== $this->limit && $cnt > $this->offset + $this->limit) {
                break;
            }
            $cnt++;

            yield $dataIdentifier => $this->dataCategory;
        }
    }
}
