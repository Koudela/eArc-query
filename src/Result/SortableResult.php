<?php declare(strict_types=1);
/**
 * e-Arc Framework - the explicit Architecture Framework
 *
 * @package earc/query-language
 * @link https://github.com/Koudela/eArc-query-language/
 * @copyright Copyright (c) 2020 Thomas Koudela
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace eArc\QueryLanguage\Result;

use Traversable;

class SortableResult extends LimitableResult
{
    public function sortAsc(string ...$dataProperty): LimitableResult
    {
        $this->sort(true, $dataProperty);

        return new LimitableResult($this->queryIndexService, $this->allowedDataIdentifiers, $this->dataCategory, $this->items);
    }

    public function sortDesc(string ...$dataProperty): LimitableResult
    {
        $this->sort(false, $dataProperty);

        return new LimitableResult($this->queryIndexService, $this->allowedDataIdentifiers, $this->dataCategory, $this->items);
    }

    protected function sort(bool $sortAsc, array $dataPropertyNames)
    {
        $this->items = $this->queryIndexService->sort($sortAsc, $this->dataCategory, $dataPropertyNames, $this->allowedDataIdentifiers, $this->items);

        $sortModifier = $sortAsc ? 1 : -1;

        if ($this->items instanceof Traversable) {
            $this->items = iterator_to_array($this->items);
        }

        uksort($this->items, function (string $dataIdentifierA, string $dataIdentifierB) use ($dataPropertyNames, $sortModifier) {
            foreach ($dataPropertyNames as $dataPropertyName) {
                return $sortModifier * $this->queryIndexService->cmp($this->dataCategory, $dataPropertyName, $dataIdentifierA, $dataIdentifierB);
            }

            return 0;
        });
    }
}
