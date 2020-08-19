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

class QueryPart extends Collector
{
    public function __construct()
    {
        parent::__construct(null);
    }

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
}
