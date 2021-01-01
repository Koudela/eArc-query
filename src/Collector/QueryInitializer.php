<?php declare(strict_types=1);
/**
 * e-Arc Framework - the explicit Architecture Framework
 *
 * @package earc/query-language
 * @link https://github.com/Koudela/eArc-query-language/
 * @copyright Copyright (c) 2020-2021 Thomas Koudela
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace eArc\QueryLanguage\Collector;

use eArc\QueryLanguage\Exception\Interfaces\QueryExceptionInterface;
use eArc\QueryLanguage\Exception\QueryException;
use eArc\QueryLanguage\Interfaces\ResolverInterface;

class QueryInitializer extends Collector
{
    public function __construct(ResolverInterface $resolver = null, ?string $type = null, ?iterable $arguments = null)
    {
        parent::__construct($resolver, $type, $arguments);
    }

    /**
     * @param string $dataCategory
     *
     * @return QueryInitializerExtended
     *
     * @throws QueryExceptionInterface
     */
    public function from(string $dataCategory): QueryInitializerExtended
    {
        return new QueryInitializerExtended($this, 'from', $dataCategory);
    }
}
