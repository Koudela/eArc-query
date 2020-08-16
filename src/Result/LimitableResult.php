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

class LimitableResult extends QueryResult
{
    public function limit(int $limit, int $offset = 0)
    {
        return new QueryResult($this->queryIndexService, $this->allowedDataIdentifiers, $this->dataCategory, $this->items, $limit, $offset);
    }
}
