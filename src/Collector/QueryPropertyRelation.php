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

class QueryPropertyRelation extends Collector
{
    /**
     * @param array $value
     *
     * @return QueryConjunctionExtended
     *
     * @throws QueryExceptionInterface
     */
    public function in(array $value): QueryConjunctionExtended
    {
        return new QueryConjunctionExtended($this, 'IN', $value);
    }

    /**
     * @param array $value
     *
     * @return QueryConjunctionExtended
     *
     * @throws QueryExceptionInterface
     */
    public function notIn(array $value): QueryConjunctionExtended
    {
        return new QueryConjunctionExtended($this, 'NOT IN', $value);
    }

    /**
     * @param mixed $value
     *
     * @return QueryConjunctionExtended
     *
     * @throws QueryExceptionInterface
     */
    public function equals($value): QueryConjunctionExtended
    {
        return new QueryConjunctionExtended($this, '=', $value);
    }

    /**
     * @param mixed $value
     *
     * @return QueryConjunctionExtended
     *
     * @throws QueryExceptionInterface
     */
    public function notEqual($value): QueryConjunctionExtended
    {
        return new QueryConjunctionExtended($this, '!=', $value);
    }

    /**
     * @param mixed $value
     *
     * @return QueryConjunctionExtended
     *
     * @throws QueryExceptionInterface
     */
    public function lt($value): QueryConjunctionExtended
    {
        return new QueryConjunctionExtended($this, '<', $value);
    }

    /**
     * @param mixed $value
     *
     * @return QueryConjunctionExtended
     *
     * @throws QueryExceptionInterface
     */
    public function leq($value): QueryConjunctionExtended
    {
        return new QueryConjunctionExtended($this, '<=', $value);
    }

    /**
     * @param mixed $value
     *
     * @return QueryConjunctionExtended
     *
     * @throws QueryExceptionInterface
     */
    public function gt($value): QueryConjunctionExtended
    {
        return new QueryConjunctionExtended($this, '>', $value);
    }

    /**
     * @param mixed $value
     *
     * @return QueryConjunctionExtended
     *
     * @throws QueryExceptionInterface
     */
    public function geq($value): QueryConjunctionExtended
    {
        return new QueryConjunctionExtended($this, '>=', $value);
    }
}
