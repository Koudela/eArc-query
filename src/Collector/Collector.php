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
use eArc\QueryLanguage\Interfaces\ResolverInterface;
use IteratorAggregate;
use Traversable;

class Collector implements IteratorAggregate
{
    /** @var ResolverInterface|null */
    protected $resolver;
    /** @var mixed[] */
    protected $args;

    /**
     * @param ResolverInterface|Collector|null $predecessor
     * @param mixed ...$args
     *
     * @throws QueryException
     */
    public function __construct($predecessor, ...$args)
    {
        if ($predecessor  instanceof ResolverInterface) {
            $this->resolver = $predecessor;
        } else if ($predecessor instanceof Collector) {
            $this->resolver = $predecessor->resolver;
        } else if (null !== $predecessor) {
            throw new QueryException(sprintf(
                'Predecessor has to be of type %s, type %s or null.',
                ResolverInterface::class,
                Collector::class
            ));
        }

        array_unshift($args, $predecessor);
        $this->args = $args;
    }

    public function getIterator(): Traversable
    {
        if ($this->args[0] instanceof Collector) {
            foreach ($this->args[0] as $args) {
                yield $args;
            }
        }

        yield $this->args;
    }
}
