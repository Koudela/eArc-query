<?php declare(strict_types=1);
/**
 * e-Arc Framework - the explicit Architecture Framework
 *
 * @package earc/query-language
 * @link https://github.com/Koudela/eArc-query-language/
 * @copyright Copyright (c) 2020 Thomas Koudela
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace eArc\QueryLanguage\Collector;

use eArc\QueryLanguage\Exception\Interfaces\QueryExceptionInterface;

class QueryInitializerExtended extends QueryInitializerSortable
{
    /**
     * @param int $limit
     * @param int $offset
     *
     * @return QueryInitializerSortable
     *
     * @throws QueryExceptionInterface
     */
    public function limit(int $limit, int $offset = 0): QueryInitializerSortable
    {
        return new QueryInitializerSortable($this, 'limit', $limit, $offset);
    }
}
