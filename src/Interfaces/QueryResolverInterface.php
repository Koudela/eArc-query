<?php declare(strict_types=1);
/**
 * e-Arc Framework - the explicit Architecture Framework
 *
 * @package earc/query-language
 * @link https://github.com/Koudela/eArc-query-language/
 * @copyright Copyright (c) 2020 Thomas Koudela
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace eArc\QueryLanguage\Interfaces;

use eArc\QueryLanguage\Collector\QueryConjunction;
use eArc\QueryLanguage\Exception\Interfaces\QueryExceptionInterface;

interface QueryResolverInterface
{
    /**
     * @param QueryIndexServiceInterface $queryIndexService
     * @param QueryConjunction $queryConjunction
     *
     * @return mixed
     *
     * @throws QueryExceptionInterface
     */
    public function eval(QueryIndexServiceInterface $queryIndexService, QueryConjunction $queryConjunction);
}
