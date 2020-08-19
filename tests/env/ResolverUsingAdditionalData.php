<?php declare(strict_types=1);

namespace eArc\QueryLanguageTest\env;

use eArc\QueryLanguage\AbstractResolver;
use Traversable;

class ResolverUsingAdditionalData extends AbstractResolver
{
    protected function queryRelation(string $dataCategory, string $dataProperty, string $cmp, $value, ?iterable $allowedDataIdentifiers = null): iterable
    {
        if ($allowedDataIdentifiers instanceof Traversable) {
            $allowedDataIdentifiers = iterator_to_array($allowedDataIdentifiers);
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
