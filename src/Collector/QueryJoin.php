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

class QueryJoin extends Collector
{
    /**
     * @param string $dataCategory
     * @param string $dataProperty
     *
     * @return QueryConjunctionSimple
     *
     * @throws QueryExceptionInterface
     */
    public function ON(string $dataCategory, string $dataProperty): QueryConjunctionSimple
    {
        return new QueryConjunctionSimple($this, 'ON', $dataCategory, $dataProperty);
    }
}
