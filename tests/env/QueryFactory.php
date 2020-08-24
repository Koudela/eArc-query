<?php declare(strict_types=1);

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
