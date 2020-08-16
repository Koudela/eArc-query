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

class QueryPart extends QueryConjunctionSimple
{
    public function __construct()
    {
        parent::__construct(null);
    }

    public static function build()
    {
        return new QueryPart();
    }
}
