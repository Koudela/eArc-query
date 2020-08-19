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

use eArc\QueryLanguage\Collector\QueryInitializer;
use eArc\QueryLanguage\Interfaces\ResolverInterface;
use eArc\QueryLanguage\Interfaces\QueryInterface;

abstract class AbstractQueryFactory implements QueryInterface
{
    public function find(?iterable $allowedDataIdentifiers = null): QueryInitializer
    {
        return new QueryInitializer($this->getQueryResolver(), 'allowedDataIdentifiers', $allowedDataIdentifiers);
    }

    public function findBy(string $dataCategory, array $keyValuePairs, ?iterable $allowedDataIdentifiers = null): iterable
    {
        $conjunction = (new QueryInitializer($this->getQueryResolver(), 'allowedDataIdentifiers', $allowedDataIdentifiers));

        foreach ($keyValuePairs as $dataPropertyName => $value) {
            if ($conjunction instanceof QueryInitializer) {
                $conjunction = $conjunction->from($dataCategory)->where($dataPropertyName)->equals($value);

                continue;
            }
            $conjunction = $conjunction->andWhere($dataPropertyName)->equals($value);
        }

        return $conjunction->eval();
    }

    abstract protected function getQueryResolver(): ResolverInterface;
}
