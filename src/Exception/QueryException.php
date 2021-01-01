<?php declare(strict_types=1);
/**
 * e-Arc Framework - the explicit Architecture Framework
 *
 * @package earc/query-language
 * @link https://github.com/Koudela/eArc-query-language/
 * @copyright Copyright (c) 2020-2021 Thomas Koudela
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace eArc\QueryLanguage\Exception;

use eArc\QueryLanguage\Exception\Interfaces\QueryExceptionInterface;
use Exception;

class QueryException extends Exception implements QueryExceptionInterface
{
}
