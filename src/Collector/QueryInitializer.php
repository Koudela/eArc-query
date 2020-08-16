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

use eArc\QueryLanguage\Exception\QueryException;
use eArc\QueryLanguage\Interfaces\ParameterInterface;
use eArc\QueryLanguage\Interfaces\QueryIndexServiceInterface;

class QueryInitializer extends Collector
{
    public function __construct(?QueryIndexServiceInterface $queryIndexService = null, ?string $type = null, ?array $arguments = null)
    {
        if (null === $queryIndexService) {
            $queryIndexService = di_get(di_param(ParameterInterface::DEFAULT_QUERY_INDEX_SERVICE));
        }

        parent::__construct($queryIndexService, $type, $arguments);
    }

//    /**
//     * @param string $dataCategory
//     *
//     * @return SortableResult
//     */
//    public function all(string $dataCategory): SortableResult
//    {
//        if (array_key_exists(2, $this->args) && $this->args[1] === 'allowedDataIdentifiers') {
//            return new SortableResult($this->queryIndexService, null, $dataCategory, $this->args[2]);
//        }
//
//        return new SortableResult($this->queryIndexService, null, $dataCategory, $this->queryIndexService->findAll($dataCategory));
//    }

    /**
     * @param string $dataCategory
     *
     * @return QueryInitializerExtended
     *
     * @throws QueryException
     */
    public function from(string $dataCategory): QueryInitializerExtended
    {
        return new QueryInitializerExtended($this, 'from', $dataCategory);
    }
}
