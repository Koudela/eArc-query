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

use eArc\QueryLanguage\Exception\Interfaces\QueryExceptionInterface;

interface QueryIndexServiceInterface
{
    /**
     * @param string $dataCategory
     *
     * @return iterable
     *
     * @throws QueryExceptionInterface
     */
    public function findAll(string $dataCategory): iterable;

    /**
     * @param bool $sortAsc
     * @param string $dataCategory
     * @param iterable $dataPropertyNames
     * @param array $allowedDataIdentifiers
     * @param array $dataIdentifiers
     *
     * @return array
     */
    public function sort(bool $sortAsc, string $dataCategory, iterable $dataPropertyNames, array $allowedDataIdentifiers, array $dataIdentifiers);

    /**
     * @param string $dataCategory
     * @param string $dataProperty
     * @param string $cmp
     * @param string|string[] $value
     *
     * @return array|string[]
     *
     * @throws QueryExceptionInterface
     */
    public function queryRelation(string $dataCategory, string $dataProperty, string $cmp, $value): array;

    /**
     * Returns all data identifiers out of $dataIdentifier1 which qualify by the
     * join and the data identifiers out of $dataIdentifier2. If
     * $dataIdentifier2 is null, there is no restriction on the right hand side.
     *
     * @param string $dataCategory1
     * @param string $dataProperty1
     * @param array $dataIdentifier1
     * @param string $dataCategory2
     * @param string $dataProperty2
     * @param array|null $dataIdentifier2
     *
     * @return array
     *
     * @throws QueryExceptionInterface
     */
    public function calculateJoin(
        string $dataCategory1,
        string $dataProperty1,
        array $dataIdentifier1,
        string $dataCategory2,
        string $dataProperty2,
        ?array $dataIdentifier2
    ): array;

    /**
     * @return QueryResolverInterface
     *
     * @throws QueryExceptionInterface
     */
    public function getQueryResolver(): QueryResolverInterface;
}
