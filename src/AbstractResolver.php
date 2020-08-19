<?php declare(strict_types=1);
/**
 * e-Arc Framework - the explicit Architecture Framework
 *
 * @package earc/query-language
 * @link https://github.com/Koudela/eArc-query-language/
 * @copyright Copyright (c) 2020 Thomas Koudela
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace eArc\QueryLanguage;

use eArc\QueryLanguage\Collector\QueryConjunctionExtended;
use eArc\QueryLanguage\Collector\QueryConjunction;
use eArc\QueryLanguage\Exception\Interfaces\QueryExceptionInterface;
use eArc\QueryLanguage\Exception\QueryException;
use eArc\QueryLanguage\Interfaces\ResolverInterface;
use eArc\QueryLanguage\Result\QueryResult;
use Traversable;

abstract class AbstractResolver implements ResolverInterface
{
    /**
     * @param QueryConjunction $queryConjunction
     * @param string|null $dataCategory
     *
     * @return QueryResult
     *
     * @throws QueryException
     */
    public function eval(QueryConjunction $queryConjunction, ?string $dataCategory = null): QueryResult
    {
        /** @var string[]|null $allowedDataIdentifiers */
        $allowedDataIdentifiers = null;

        foreach ($queryConjunction as $args) {
            switch ($args[1])  {
                case 'allowedDataIdentifiers':
                    $allowedDataIdentifiers = $args[2];
                    break;
                case 'limit':
                    $limit = $args[2];
                    $offset = $args[3];
                    break;
                case 'sortAsc':
                case 'sortDesc':
                    $sort = $args[1] === 'sortAsc' ? 'asc' : 'desc';
                    $sortBy = $args[2];
                    break;
                case 'from':
                    $dataCategory = $args[2];
                    break;
                case 'where':
                    $lastProperty = $args[2];
                    break;
                case 'andWhere':
                case 'orWhere':
                    if (!isset($conjValue1)) {
                        throw new QueryException('This is not allowed to happen. Most probably there is a flaw in the query language logic.');
                    }
                    $conjValue2 = $conjValue1;
                    unset($conjValue1);
                    $lastProperty = $args[2];
                    $conj = $args[1] === 'andWhere' ? 'AND' : 'OR';
                    break;
                case 'BRACKETS':
                    $conjValue1 = $this->eval($args[2], $dataCategory);
                    break;
                case 'AND':
                case 'OR':
                    if (!isset($conjValue1)) {
                        throw new QueryException('This is not allowed to happen. Most probably there is a flaw in the query language logic.');
                    }
                    if (!$args[2] instanceof QueryConjunctionExtended) {
                        throw new QueryException(sprintf(
                            'Argument of %s::%s() has to be of Type %s',
                            QueryConjunctionExtended::class,
                            $args[1],
                            QueryConjunctionExtended::class
                        ));
                    }
                    $conjValue1 = $this->evalConjunction(
                        $args[1],
                        $conjValue1,
                        $this->eval($args[2], $dataCategory)
                    );
                    break;
                case 'IN':
                case 'NOT IN':
                case '=':
                case '!=':
                case '<':
                case '<=':
                case '>':
                case '>=':
                    $lastValue = $args[2];
                    $lastCmp = $args[1];
                    if (!isset($lastProperty) || is_null($dataCategory)) {
                        throw new QueryException('This is not allowed to happen. Most probably there is a flaw in the query language logic.');
                    }
                    $conjValue1 = $this->queryRelation($dataCategory, $lastProperty, $lastCmp, $lastValue, $allowedDataIdentifiers);
                    if (isset($conj)) {
                        if (!isset($conjValue2)) {
                            throw new QueryException('This is not allowed to happen. Most probably there is a flaw in the query language logic.');
                        }
                        $conjValue1 = $this->evalConjunction($conj, $conjValue1, $conjValue2);

                        unset($conj);
                        unset($conjValue2);
                    }
                    break;
            }
        }

        if (is_null($dataCategory)) {
            throw new QueryException('This is not allowed to happen. Most probably there is a flaw in the query language logic.');
        }

        return new QueryResult(
            $this,
            $dataCategory,
            $conjValue1 ?? null,
            $allowedDataIdentifiers ?? null,
            $limit ?? 0,
            $offset ?? 0,
            $sort ?? null,
            $sortBy ?? null
        );
    }

    /**
     * @param string $type
     * @param iterable $val1
     * @param iterable $val2
     *
     * @return array
     *
     * @throws QueryExceptionInterface
     */
    protected function evalConjunction(string $type, iterable $val1, iterable $val2): array
    {
        if ($val1 instanceof Traversable) {
            $val1 = iterator_to_array($val1);
        }

        if ($val2 instanceof Traversable) {
            $val2 = iterator_to_array($val2);
        }

        if ($type === 'AND') {
            return array_intersect_key(array_merge_recursive($val1, $val2), $val2);
        }

        if ($type === 'OR') {
            return array_merge_recursive($val1, $val2);
        }

        throw new QueryException('unknown conjunction type');
    }

    /**
     * @param string $dataCategory
     * @param string $dataProperty
     * @param string $cmp
     * @param mixed $value
     * @param iterable|null $allowedDataIdentifiers [!]you may ignore this information or use it to speed up the data retrieval
     *
     * @return iterable
     *
     * @throws QueryExceptionInterface
     */
    abstract protected function queryRelation(string $dataCategory, string $dataProperty, string $cmp, $value, ?iterable $allowedDataIdentifiers = null): iterable;

}
