<?php declare(strict_types=1);
/**
 * e-Arc Framework - the explicit Architecture Framework
 *
 * @package earc/query-language
 * @link https://github.com/Koudela/eArc-query-language/
 * @copyright Copyright (c) 2020 Thomas Koudela
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace eArc\QueryLanguage\Bridge;

use eArc\QueryLanguage\Collector\QueryPropertyRelation;
use eArc\QueryLanguage\Collector\QueryConjunction;
use eArc\QueryLanguage\Collector\QueryInitializer;
use eArc\QueryLanguage\Collector\QueryJoin;
use eArc\QueryLanguage\Exception\Interfaces\QueryExceptionInterface;
use eArc\QueryLanguage\Exception\QueryException;
use eArc\QueryLanguage\Interfaces\QueryIndexServiceInterface;

class DoctrineQueryResolver
{
    /**
     * @param QueryIndexServiceInterface $queryIndexService
     * @param QueryConjunction $queryConjunction
     *
     * @return mixed
     *
     * @throws QueryExceptionInterface
     */
    public function eval(QueryIndexServiceInterface $queryIndexService, QueryConjunction $queryConjunction)
    {
        return /** TODO */;
    }

    /**
     * @param QueryIndexServiceInterface $queryIndexService
     * @param QueryConjunction $queryConjunction
     *
     * @return iterable
     *
     * @throws QueryExceptionInterface
     */
    protected function evaluate(QueryIndexServiceInterface $queryIndexService, QueryConjunction $queryConjunction): iterable
    {
        /** @var string */
        $primaryDataCategory = '';
        /** @var string[]|null */
        $allowedDataIdentifiers = null;
        /** @var int */
        $joinId = 0;
        /** @var array */
        $joins = [];
        /** @var array */
        $values = [];

        foreach ($queryConjunction as $args) {
            switch ($args[1])  {
                case 'allowedDataIdentifiers':
                    $allowedDataIdentifiers = $args[2];
                    break;
                case 'from':
                    $lastCategory = $args[2];
                    $primaryDataCategory = $lastCategory;
                    break;
                case 'where':
                    if (!$args[0] instanceof QueryInitializer || !$args[0] instanceof QueryJoin) {
                        throw new QueryException(sprintf(
                            '%s::where() has to be a successor of %s or %s',
                            QueryConjunction::class,
                            QueryInitializer::class,
                            QueryJoin::class
                        ));
                    }
                    $lastProperty = $args[2];
                    break;
                case 'andWhere':
                case 'orWhere':
                    if (!$args[0] instanceof QueryPropertyRelation || !isset($conjValue1)) {
                        throw new QueryException(sprintf(
                            '%s::%s() has to be a successor of %s',
                            QueryConjunction::class,
                            $args[1],
                            QueryPropertyRelation::class
                        ));
                    }
                    $conjValue2 = $conjValue1;
                    unset($conjValue1);
                    $lastProperty = $args[2];
                    $conj = $args[1] === 'andWhere' ? 'AND' : 'OR';
                    break;
                case 'AND':
                case 'OR':
                    if (!$args[0] instanceof QueryPropertyRelation || !isset($conjValue1)) {
                        throw new QueryException(sprintf(
                            '%s::%s() has to be a successor of %s',
                            QueryConjunction::class,
                            $args[1],
                            QueryPropertyRelation::class
                        ));
                    }
                    if (!$args[2] instanceof QueryConjunction) {
                        throw new QueryException(sprintf(
                            'Argument of %s::%s() has to be of Type %s',
                            QueryConjunction::class,
                            $args[1],
                            QueryConjunction::class
                        ));
                    }
                    $conjValue1 = $this->evalConjunction($args[1], $conjValue1, $this->evaluate($queryIndexService, $args[2]));
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
                    if (!isset($lastProperty) || !isset($lastCategory)) {
                        throw new QueryException('This is not allowed to happen. Most probably there is a flaw in the query language logic.');
                    }
                    $conjValue1 = $queryIndexService->queryRelation($lastCategory, $lastProperty, $lastCmp, $lastValue);
                    if (isset($conj)) {
                        if (!isset($conjValue2)) {
                            throw new QueryException('This is not allowed to happen. Most probably there is a flaw in the query language logic.');
                        }
                        $conjValue1 = $this->evalConjunction($conj, $conjValue1, $conjValue2);

                        unset($conj);
                        unset($conjValue2);
                    }
                    break;
                case 'JOIN':
                    if (!isset($lastCategory)) {
                        throw new QueryException('This is not allowed to happen. Most probably there is a flaw in the query language logic.');
                    }
                    if (!isset($conjValue1)) {
                        $conjValue1 = $queryIndexService->findAll($lastCategory);
                    }
                    $values[$joinId] = $conjValue1;
                    unset($conjValue1);
                    $lastCategory = $args[2];
                    $lastProperty = $args[3];
                    break;
                case 'ON':
                    if (!isset($lastCategory) || !isset($lastProperty)) {
                        throw new QueryException('This is not allowed to happen. Most probably there is a flaw in the query language logic.');
                    }
                    $joinCategory = $args[2];
                    $joinProperty = $args[3];
                    $joins[++$joinId] = [$lastCategory, $lastProperty, $joinCategory, $joinProperty];
                    $lastCategory = $joinCategory;
                    $lastProperty = $joinProperty;
                    break;
            }
        }

        if (!array_key_exists($joinId, $values)) {
            $values[$joinId] = null;
        }

        for (;$joinId > 0;$joinId--) {
            $join = $joins[$joinId];
            $values[$join[$joinId-1]] = $queryIndexService->calculateJoin(
                $join[0],
                $join[1],
                $values[$joinId-1],
                $join[2],
                $join[3],
                $values[$joinId]
            );
        }

        if (!isset($lastCategory)) {
            throw new QueryException('This is not allowed to happen. Most probably there is a flaw in the query language logic.');
        }

        return null !== $values[0] ? $values[0] : $queryIndexService->findAll($lastCategory);
    }

    protected function evalConjunction(string $type, array $val1, array $val2): array
    {
        if ($type === 'AND') {
            return array_intersect_key(array_merge_recursive($val1, $val2), $val2);
        }

        if ($type === 'OR') {
            return array_merge_recursive($val1, $val2);
        }

        throw new QueryException('unknown conjunction type');
    }
}
