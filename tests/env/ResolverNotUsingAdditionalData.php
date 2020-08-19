<?php declare(strict_types=1);

namespace eArc\QueryLanguageTest\env;

use eArc\QueryLanguage\AbstractResolver;
use Traversable;

class ResolverNotUsingAdditionalData extends AbstractResolver
{
    protected function queryRelation(string $dataCategory, string $dataProperty, string $cmp, $value, ?iterable $allowedDataIdentifiers = null): iterable
    {
        $items = [];

        foreach ($this->findAll($dataCategory) as $id => $item) {
            switch ($cmp) {
                case 'IN':
                    foreach ($value as $val) {
                        if (array_key_exists($dataProperty, $item) && $item === $val) {
                            $items[$id] = $item;

                            break;
                        }
                    }
                    break;
                case 'NOT IN':
                case '=':
                case '!=':
                case '<':
                case '<=':
                case '>':
                case '>=':
            }
        }
    }

    public function findAll(string $dataCategory): iterable
    {
        static $data = [
            'A' => [
                1 => ['color' => 'blue', 'shape' => 'circle']
            ],
            'B' => [

            ],
            'C' => [

            ],
        ];

        return array_key_exists($dataCategory, $data) ? $data[$dataCategory] : [];
    }

    public function sort(string $dataCategory, string $sort, iterable $dataPropertyNames, ?iterable $dataIdentifiers, ?iterable $allowedDataIdentifiers = null, int $limit = 0, int $offset = 0): iterable
    {
        if ($dataPropertyNames instanceof Traversable) {
            $dataPropertyNames = iterator_to_array($dataPropertyNames);
        }
    }
}
