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

class QueryConjunctionSimpleExtended extends QueryConjunction
{
    /**
     * @param string $dataProperty
     *
     * @return QueryPropertyRelation
     *
     * @throws QueryExceptionInterface
     */
    public function where(string $dataProperty): QueryPropertyRelation
    {
        return new QueryPropertyRelation($this, 'where', $dataProperty);
    }

    /**
     * @param QueryConjunctionExtended $query
     *
     * @return QueryConjunctionExtended
     *
     * @throws QueryExceptionInterface
     */
    public function BRACKETS(QueryConjunctionExtended $query): QueryConjunctionExtended
    {
        return new QueryConjunctionExtended($this, 'BRACKETS', $query);
    }
}
