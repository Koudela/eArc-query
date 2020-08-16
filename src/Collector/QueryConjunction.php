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

class QueryConjunction extends Collector
{
    /**
     * @param string $dataCategory
     * @param string $dataProperty
     *
     * @return QueryJoin
     *
     * @throws QueryExceptionInterface
     */
    public function JOIN(string $dataCategory, string $dataProperty): QueryJoin
    {
        return new QueryJoin($this, 'JOIN', $dataCategory, $dataProperty);
    }

    /**
     * @return mixed
     *
     * @throws QueryExceptionInterface
     */
    public function eval()
    {
        return $this->queryIndexService->getQueryResolver()->eval($this->queryIndexService, $this);
    }
}
