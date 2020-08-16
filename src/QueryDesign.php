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

$a = ['pk1' => ['color' => 'purple'], 'pk2' => ['color' => 'blue']];
$b = ['pk1' => ['color' => 'purple'], 'pk3' => ['color' => 'orange']];
//$a OR $b
$c = $a + $b;
//$a AND $b
$c = array_intersect_key($a, $b);
/**
 * @method QueryInitializerExtended from(string $dataCategory)
 */
class QueryInitializer {}

/**
 * @method QueryInitializerSortable limit(int $limit, int $offset = 0)
 */
class QueryInitializerExtended extends QueryInitializerSortable {}

/**
 * @method QueryConjunctionSimpleExtended sortAsc(string ...$dataProperty)
 * @method QueryConjunctionSimpleExtended sortDesc(string ...$dataProperty)
 */
class QueryInitializerSortable extends QueryConjunctionSimpleExtended {}

/**
 * @method QueryPropertyRelation orWhere(string $dataProperty)
 * @method QueryPropertyRelation andWhere(string $dataProperty)
 * @method QueryConjunctionExtended OR(QueryConjunctionExtended $query)
 * @method QueryConjunctionExtended AND(QueryConjunctionExtended $query)
 */
class QueryConjunctionExtended extends QueryConjunction {}

/**
 * @method QueryPropertyRelation where(string $dataProperty)
 */
class QueryConjunctionSimpleExtended extends QueryConjunction {}

/**
 * @method QueryJoin JOIN(string $dataCategory, string $dataProperty)
 * @method QueryResult eval()
 */
class QueryConjunction {}

/**
 * @method QueryPropertyRelation where(string $dataProperty)
 */
class QueryConjunctionSimple {}

/**
 * @method QueryConjunctionExtended in(array $value)
 * @method QueryConjunctionExtended notIn(array $value)
 * @method QueryConjunctionExtended equals($value)
 * @method QueryConjunctionExtended notEqual($value)
 * @method QueryConjunctionExtended lt($value)
 * @method QueryConjunctionExtended leq($value)
 * @method QueryConjunctionExtended gt($value)
 * @method QueryConjunctionExtended geq($value)
 */
class QueryPropertyRelation {}


/**
 * @method QueryConjunctionSimple ON(string $dataCategory, string $dataProperty)
 */
class QueryJoin {}

class QueryPart extends QueryConjunctionSimple {}

class QueryResult implements \IteratorAggregate {}

(new QueryInitializer())->from('')
    ->limit(0,0)
    ->sortAsc('')
    ->where('')
    ->notEqual('')
    ->AND((new QueryPart())->where('')->equals(''))
    ->JOIN('','')->ON('','')
    ->where('')->equals('')->eval();
