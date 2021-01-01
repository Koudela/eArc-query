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

class QueryConjunctionExtended extends QueryConjunction
{
    /**
     * @param string $dataProperty
     *
     * @return QueryPropertyRelation
     *
     * @throws QueryExceptionInterface
     */
    public function orWhere(string $dataProperty): QueryPropertyRelation
    {
        return new QueryPropertyRelation($this, 'orWhere', $dataProperty);
    }

    /**
     * @param string $dataProperty
     *
     * @return QueryPropertyRelation
     *
     * @throws QueryExceptionInterface
     */
    public function andWhere(string $dataProperty): QueryPropertyRelation
    {
        return new QueryPropertyRelation($this, 'andWhere', $dataProperty);
    }

    /**
     * @param QueryConjunctionExtended $query
     *
     * @return QueryConjunctionExtended
     *
     * @throws QueryExceptionInterface
     */
    public function OR(QueryConjunctionExtended $query): QueryConjunctionExtended
    {
        return new QueryConjunctionExtended($this, 'OR', $query);
    }

    /**
     * @param QueryConjunctionExtended $query
     *
     * @return QueryConjunctionExtended
     *
     * @throws QueryExceptionInterface
     */
    public function AND(QueryConjunctionExtended $query): QueryConjunctionExtended
    {
        return new QueryConjunctionExtended($this, 'AND', $query);
    }
}
