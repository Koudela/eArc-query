<?php declare(strict_types=1);
/**
 * e-Arc Framework - the explicit Architecture Framework
 *
 * @package earc/query-language
 * @link https://github.com/Koudela/eArc-query-language/
 * @copyright Copyright (c) 2020-2021 Thomas Koudela
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace eArc\QueryLanguageTest\env;

use eArc\QueryLanguage\AbstractQueryFactory;
use eArc\QueryLanguage\Interfaces\ResolverInterface;

class QueryFactory extends AbstractQueryFactory
{
    protected function getQueryResolver(): ResolverInterface
    {
        return di_get(Resolver::class);
    }
}
