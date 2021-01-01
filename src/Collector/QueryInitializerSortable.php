<?php declare(strict_types=1);
/**
 * e-Arc Framework - the explicit Architecture Framework
 *
 * @package earc/query-language
 * @link https://github.com/Koudela/eArc-query-language/
 * @copyright Copyright (c) 2020-2021 Thomas Koudela
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace eArc\QueryLanguage\Collector;

use eArc\QueryLanguage\Exception\Interfaces\QueryExceptionInterface;

class QueryInitializerSortable extends QueryConjunctionSimpleExtended
{
    /**
     * @param string ...$dataProperty
     *
     * @return QueryConjunctionSimpleExtended
     *
     * @throws QueryExceptionInterface
     */
    public function sortAsc(string ...$dataProperty): QueryConjunctionSimpleExtended
    {
        return new QueryConjunctionSimpleExtended($this, 'sortAsc', $dataProperty);
    }

    /**
     * @param string ...$dataProperty
     *
     * @return QueryConjunctionSimpleExtended
     *
     * @throws QueryExceptionInterface
     */
    public function sortDesc(string ...$dataProperty): QueryConjunctionSimpleExtended
    {
        return new QueryConjunctionSimpleExtended($this, 'sortDesc', $dataProperty);
    }
}
