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

interface ResolverInterface
{
    const SORT_ASC = 'asc';
    const SORT_DESC = 'desc';

    /**
     * @param string $dataCategory
     *
     * @return iterable
     *
     * @throws QueryExceptionInterface
     */
    public function findAll(string $dataCategory): iterable;

    /**
     * @param QueryConjunction $queryConjunction
     *
     * @return mixed
     *
     * @throws QueryExceptionInterface
     */
    public function eval(QueryConjunction $queryConjunction);

    /**
     * @param string $dataCategory
     * @param string $sort
     * @param iterable $dataPropertyNames
     * @param iterable|null $dataItems
     * @param iterable|null $allowedDataIdentifiers [!]you may ignore this information or use it to speed up your sorting
     * @param int $limit [!]you may ignore this information or use it to speed up your sorting
     * @param int $offset [!]you may ignore this information or use it to speed up your sorting
     *
     * @return iterable
     */
    public function sort(string $dataCategory, string $sort, iterable $dataPropertyNames, ?iterable $dataItems, ?iterable $allowedDataIdentifiers = null, int $limit = 0, int $offset = 0): iterable;
}
