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

use eArc\QueryLanguage\AbstractResolver;
use eArc\QueryLanguage\Interfaces\ResolverInterface;
use Traversable;

class Resolver extends AbstractResolver
{
    public static $data = [];

    protected function queryRelation(string $dataCategory, string $dataProperty, string $cmp, $value, ?iterable $allowedDataIdentifiers = null): iterable
    {
        $items = [];

        foreach ($this->findAll($dataCategory) as $id => $item) {
            switch ($cmp) {
                case 'IN':
                    foreach ($value as $val) {
                        if (array_key_exists($dataProperty, $item)) {
                            if ($item[$dataProperty] === $val) {
                                $items[$id] = $item;

                                break;
                            }
                        } else {
                            if (is_null($val)) {
                                $items[$id] = $item;

                                break;
                            }
                        }
                    }
                    break;
                case 'NOT IN':
                    $found = false;
                    foreach ($value as $val) {
                        if (array_key_exists($dataProperty, $item)) {
                            if ($item[$dataProperty] === $val) {
                                $found = true;

                                break;
                            }
                        } else {
                            if (is_null($val)) {
                                $found = true;

                                break;
                            }
                        }
                    }
                    if (!$found) {
                        $items[$id] = $item;
                    }
                    break;
                case '=':
                    if (!array_key_exists($dataProperty, $item) && $value === null) {
                        $items[$id] = $item;
                    } else if (array_key_exists($dataProperty, $item) && $item[$dataProperty] === $value) {
                        $items[$id] = $item;
                    }
                    break;
                case '!=':
                    if (array_key_exists($dataProperty, $item) && $item[$dataProperty] !== $value) {
                        $items[$id] = $item;
                    }
                    break;
                case '<':
                    if (array_key_exists($dataProperty, $item) && $item[$dataProperty] < $value) {
                        $items[$id] = $item;
                    }
                    break;
                case '<=':
                    if (array_key_exists($dataProperty, $item) && $item[$dataProperty] <= $value) {
                        $items[$id] = $item;
                    }
                    break;
                case '>':
                    if (array_key_exists($dataProperty, $item) && $item[$dataProperty] > $value) {
                        $items[$id] = $item;
                    }
                    break;
                case '>=':
                    if (array_key_exists($dataProperty, $item) && $item[$dataProperty] >= $value) {
                        $items[$id] = $item;
                    }
                    break;
            }
        }

        return $items;
    }

    public function findAll(string $dataCategory): iterable
    {
        return array_key_exists($dataCategory, self::$data) ? self::$data[$dataCategory] : [];
    }

    public function sort(string $dataCategory, string $sort, iterable $dataPropertyNames, ?iterable $dataItems, ?iterable $allowedDataIdentifiers = null, int $limit = 0, int $offset = 0): iterable
    {
        if ($dataPropertyNames instanceof Traversable) {
            $dataPropertyNames = iterator_to_array($dataPropertyNames);
        }

        if (is_null($dataItems)) {
            $dataItems = $this->findAll($dataCategory);
        }

        if ($dataItems instanceof Traversable) {
            $dataItems = iterator_to_array($dataItems);
        }

        $sortModifier = $sort === ResolverInterface::SORT_ASC ? 1 : -1;

        uksort($dataItems, function (string $dataKeyA, string $dataKeyB) use ($dataPropertyNames, $sortModifier, $dataItems) {
            $dataItemA = $dataItems[$dataKeyA];
            $dataItemB = $dataItems[$dataKeyB];
            foreach ($dataPropertyNames as $dataPropertyName) {
                if ($dataItemA[$dataPropertyName] !== $dataItemB[$dataPropertyName]) {
                    return $sortModifier * ($dataItemA[$dataPropertyName] <=> $dataItemB[$dataPropertyName]);
                }
            }

            return 0;
        });

        return $dataItems;
    }
}
