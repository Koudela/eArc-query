<?php declare(strict_types=1);
/**
 * e-Arc Framework - the explicit Architecture Framework
 *
 * @package earc/query-language
 * @link https://github.com/Koudela/eArc-query-language/
 * @copyright Copyright (c) 2020-2021 Thomas Koudela
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace eArc\QueryLanguage\Interfaces;

use eArc\QueryLanguage\Collector\QueryInitializer;
use eArc\QueryLanguage\Exception\Interfaces\QueryExceptionInterface;

interface QueryInterface
{
    /**
     * Get the data based on a query. By the allowed data identifiers argument
     * the result can be restricted to the passed data identifiers.
     *
     * @param iterable|null $allowedDataIdentifiers
     *
     * @return QueryInitializer
     *
     * @throws QueryExceptionInterface
     */
    public function select(?iterable $allowedDataIdentifiers = null): QueryInitializer;

    /**
     * Get the data for the key value pairs based on the data category. The keys
     * represent the data property name the value the value the data has to be
     * equal. Several key value pairs are conjugated by a logical AND. If the
     * key value pairs are empty all the data in the data category is returned.
     * By the allowed data identifiers argument the result can be restricted to
     * the passed data identifiers.
     *
     * @param string $dataCategory
     * @param array $keyValuePairs
     * @param iterable|null $allowedDataIdentifiers
     *
     * @return iterable
     *
     * @throws QueryExceptionInterface
     */
    public function findBy(string $dataCategory, array $keyValuePairs, ?iterable $allowedDataIdentifiers = null): iterable;
}
