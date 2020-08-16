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

use eArc\QueryLanguage\Collector\QueryInitializer;
use eArc\QueryLanguage\Exception\Interfaces\QueryExceptionInterface;

interface QueryServiceInterface
{
    /**
     * Get the data identifiers for the query based on the fully qualified class
     * name. By the allowed data identifiers argument the result can be restricted
     * to the passed data identifiers.
     *
     * @param string[]|null $allowedDataIdentifiers
     *
     * @return QueryInitializer
     *
     * @throws QueryExceptionInterface
     */
    public function find(?array $allowedDataIdentifiers = null): QueryInitializer;

    /**
     * Get the data identifiers for the key value pairs based on the fully qualified
     * class name. If the key value pairs are empty the data identifiers for all
     * entities represented by the fully qualified class name are returned. By
     * the allowed data identifiers argument the result can be restricted to the
     * passed data identifiers.
     *
     * @param string $dataCategory
     * @param array $keyValuePairs
     * @param string[]|null $allowedDataIdentifiers
     *
     * @return iterable
     *
     * @throws QueryExceptionInterface
     */
    public function findBy(string $dataCategory, array $keyValuePairs, ?array $allowedDataIdentifiers = null): iterable;
}
