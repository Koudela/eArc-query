<?php declare(strict_types=1);
/**
 * e-Arc Framework - the explicit Architecture Framework
 *
 * @package earc/query-language
 * @link https://github.com/Koudela/eArc-query-language/
 * @copyright Copyright (c) 2020-2021 Thomas Koudela
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace eArc\QueryLanguageTest;

use eArc\DI\DI;
use eArc\DI\Exceptions\InvalidArgumentException;
use eArc\QueryLanguage\Collector\QueryPart;
use eArc\QueryLanguageTest\env\QueryFactory;
use eArc\QueryLanguageTest\env\Resolver;
use PHPUnit\Framework\TestCase;
use Traversable;

/**
 * This is no unit test. It is an integration test.
 */
class IntegrationTest extends TestCase
{
    /**
     * @throws InvalidArgumentException
     */
    public function bootstrap()
    {
        DI::init();
    }

    public function useDataA()
    {
        Resolver::$data = [
            'A' => [
                '_1' => ['sort' => 1, 'color' => 'blue', 'shape' => 'circle'],
                '_2' => ['sort' => 2, 'color' => 'blue', 'shape' => 'circle', 'available' => false],
                '_3' => ['sort' => 3, 'color' => 'blue', 'shape' => 'circle', 'available' => true],
                '_4' => ['sort' => 4, 'color' => 'purple', 'shape' => 'circle'],
                '_5' => ['sort' => 5, 'color' => 'purple', 'shape' => 'circle', 'available' => false],
                '_6' => ['sort' => 6, 'color' => 'purple', 'shape' => 'circle', 'available' => true],
                '_7' => ['sort' => 7, 'color' => 'blue', 'shape' => 'rectangle'],
                '_8' => ['sort' => 8, 'color' => 'blue', 'shape' => 'rectangle', 'available' => false],
                '_9' => ['sort' => 9, 'color' => 'blue', 'shape' => 'rectangle', 'available' => true],
            ],
        ];
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testFindBy()
    {
        $this->bootstrap();
        $this->useDataA();
        $allowedDataIdentifiers = ['_1', '_2', '_3', '_4', '_5'];

        /** @var Traversable $result */
        $result = di_get(QueryFactory::class)->findBy('A', []);
        self::assertEquals(
            Resolver::$data['A'],
            iterator_to_array($result)
        );

        $result = di_get(QueryFactory::class)->findBy('A', ['color' => 'blue']);
        self::assertEquals([
            '_1' => ['sort' => 1, 'color' => 'blue', 'shape' => 'circle'],
            '_2' => ['sort' => 2, 'color' => 'blue', 'shape' => 'circle', 'available' => false],
            '_3' => ['sort' => 3, 'color' => 'blue', 'shape' => 'circle', 'available' => true],
            '_7' => ['sort' => 7, 'color' => 'blue', 'shape' => 'rectangle'],
            '_8' => ['sort' => 8, 'color' => 'blue', 'shape' => 'rectangle', 'available' => false],
            '_9' => ['sort' => 9, 'color' => 'blue', 'shape' => 'rectangle', 'available' => true],
        ], iterator_to_array($result));

        $result = di_get(QueryFactory::class)->findBy('A', ['color' => 'blue', 'available' => true]);
        self::assertEquals([
            '_3' => ['sort' => 3, 'color' => 'blue', 'shape' => 'circle', 'available' => true],
            '_9' => ['sort' => 9, 'color' => 'blue', 'shape' => 'rectangle', 'available' => true],
        ], iterator_to_array($result));

        $result = di_get(QueryFactory::class)->findBy('A', [], $allowedDataIdentifiers);
        self::assertEquals([
            '_1' => ['sort' => 1, 'color' => 'blue', 'shape' => 'circle'],
            '_2' => ['sort' => 2, 'color' => 'blue', 'shape' => 'circle', 'available' => false],
            '_3' => ['sort' => 3, 'color' => 'blue', 'shape' => 'circle', 'available' => true],
            '_4' => ['sort' => 4, 'color' => 'purple', 'shape' => 'circle'],
            '_5' => ['sort' => 5, 'color' => 'purple', 'shape' => 'circle', 'available' => false],
        ], iterator_to_array($result));

        $result = di_get(QueryFactory::class)->findBy('A', ['color' => 'blue'], $allowedDataIdentifiers);
        self::assertEquals([
            '_1' => ['sort' => 1, 'color' => 'blue', 'shape' => 'circle'],
            '_2' => ['sort' => 2, 'color' => 'blue', 'shape' => 'circle', 'available' => false],
            '_3' => ['sort' => 3, 'color' => 'blue', 'shape' => 'circle', 'available' => true],
        ], iterator_to_array($result));

        $result = di_get(QueryFactory::class)->findBy('A', ['color' => 'blue', 'available' => true], $allowedDataIdentifiers);
        self::assertEquals([
            '_3' => ['sort' => 3, 'color' => 'blue', 'shape' => 'circle', 'available' => true],
        ], iterator_to_array($result));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testFrom()
    {
        $this->bootstrap();
        $this->useDataA();
        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->eval();
        self::assertEquals(Resolver::$data['A'], iterator_to_array($result));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testEqual()
    {
        $this->bootstrap();
        $this->useDataA();
        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->where('color')
            ->equals('purple')
            ->eval();
        self::assertEquals([
            '_4' => ['sort' => 4, 'color' => 'purple', 'shape' => 'circle'],
            '_5' => ['sort' => 5, 'color' => 'purple', 'shape' => 'circle', 'available' => false],
            '_6' => ['sort' => 6, 'color' => 'purple', 'shape' => 'circle', 'available' => true],
        ], iterator_to_array($result));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testNotEqual()
    {
        $this->bootstrap();
        $this->useDataA();
        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->where('shape')
            ->notEqual('circle')
            ->eval();
        self::assertEquals([
            '_7' => ['sort' => 7, 'color' => 'blue', 'shape' => 'rectangle'],
            '_8' => ['sort' => 8, 'color' => 'blue', 'shape' => 'rectangle', 'available' => false],
            '_9' => ['sort' => 9, 'color' => 'blue', 'shape' => 'rectangle', 'available' => true],
        ], iterator_to_array($result));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testLessThan()
    {
        $this->bootstrap();
        $this->useDataA();
        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->where('sort')
            ->lt(4)
            ->eval();
        self::assertEquals([
            '_1' => ['sort' => 1, 'color' => 'blue', 'shape' => 'circle'],
            '_2' => ['sort' => 2, 'color' => 'blue', 'shape' => 'circle', 'available' => false],
            '_3' => ['sort' => 3, 'color' => 'blue', 'shape' => 'circle', 'available' => true],
        ], iterator_to_array($result));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testLessOrEqual()
    {
        $this->bootstrap();
        $this->useDataA();
        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->where('sort')
            ->leq(3)
            ->eval();
        self::assertEquals([
            '_1' => ['sort' => 1, 'color' => 'blue', 'shape' => 'circle'],
            '_2' => ['sort' => 2, 'color' => 'blue', 'shape' => 'circle', 'available' => false],
            '_3' => ['sort' => 3, 'color' => 'blue', 'shape' => 'circle', 'available' => true],
        ], iterator_to_array($result));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testGreaterThan()
    {
        $this->bootstrap();
        $this->useDataA();
        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->where('sort')
            ->gt(6)
            ->eval();
        self::assertEquals([
            '_7' => ['sort' => 7, 'color' => 'blue', 'shape' => 'rectangle'],
            '_8' => ['sort' => 8, 'color' => 'blue', 'shape' => 'rectangle', 'available' => false],
            '_9' => ['sort' => 9, 'color' => 'blue', 'shape' => 'rectangle', 'available' => true],
        ], iterator_to_array($result));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testGreaterOrEqual()
    {
        $this->bootstrap();
        $this->useDataA();
        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->where('sort')
            ->geq(7)
            ->eval();
        self::assertEquals([
            '_7' => ['sort' => 7, 'color' => 'blue', 'shape' => 'rectangle'],
            '_8' => ['sort' => 8, 'color' => 'blue', 'shape' => 'rectangle', 'available' => false],
            '_9' => ['sort' => 9, 'color' => 'blue', 'shape' => 'rectangle', 'available' => true],
        ], iterator_to_array($result));
    }


    /**
     * @throws InvalidArgumentException
     */
    public function testIn()
    {
        $this->bootstrap();
        $this->useDataA();
        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->where('sort')
            ->in([7, 8, 9])
            ->eval();
        self::assertEquals([
            '_7' => ['sort' => 7, 'color' => 'blue', 'shape' => 'rectangle'],
            '_8' => ['sort' => 8, 'color' => 'blue', 'shape' => 'rectangle', 'available' => false],
            '_9' => ['sort' => 9, 'color' => 'blue', 'shape' => 'rectangle', 'available' => true],
        ], iterator_to_array($result));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testNotIn()
    {
        $this->bootstrap();
        $this->useDataA();
        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->where('available')
            ->notIn([true, false])
            ->eval();
        self::assertEquals([
            '_1' => ['sort' => 1, 'color' => 'blue', 'shape' => 'circle'],
            '_4' => ['sort' => 4, 'color' => 'purple', 'shape' => 'circle'],
            '_7' => ['sort' => 7, 'color' => 'blue', 'shape' => 'rectangle'],
        ], iterator_to_array($result));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testSortAsc()
    {
        $this->bootstrap();
        $this->useDataA();
        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->sortAsc('color', 'sort')
            ->eval();
        self::assertSame([
            '_1' => ['sort' => 1, 'color' => 'blue', 'shape' => 'circle'],
            '_2' => ['sort' => 2, 'color' => 'blue', 'shape' => 'circle', 'available' => false],
            '_3' => ['sort' => 3, 'color' => 'blue', 'shape' => 'circle', 'available' => true],
            '_7' => ['sort' => 7, 'color' => 'blue', 'shape' => 'rectangle'],
            '_8' => ['sort' => 8, 'color' => 'blue', 'shape' => 'rectangle', 'available' => false],
            '_9' => ['sort' => 9, 'color' => 'blue', 'shape' => 'rectangle', 'available' => true],
            '_4' => ['sort' => 4, 'color' => 'purple', 'shape' => 'circle'],
            '_5' => ['sort' => 5, 'color' => 'purple', 'shape' => 'circle', 'available' => false],
            '_6' => ['sort' => 6, 'color' => 'purple', 'shape' => 'circle', 'available' => true],
        ], iterator_to_array($result));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testSortDesc()
    {
        $this->bootstrap();
        $this->useDataA();
        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->sortDesc('color', 'sort')
            ->eval();
        self::assertSame([
            '_6' => ['sort' => 6, 'color' => 'purple', 'shape' => 'circle', 'available' => true],
            '_5' => ['sort' => 5, 'color' => 'purple', 'shape' => 'circle', 'available' => false],
            '_4' => ['sort' => 4, 'color' => 'purple', 'shape' => 'circle'],
            '_9' => ['sort' => 9, 'color' => 'blue', 'shape' => 'rectangle', 'available' => true],
            '_8' => ['sort' => 8, 'color' => 'blue', 'shape' => 'rectangle', 'available' => false],
            '_7' => ['sort' => 7, 'color' => 'blue', 'shape' => 'rectangle'],
            '_3' => ['sort' => 3, 'color' => 'blue', 'shape' => 'circle', 'available' => true],
            '_2' => ['sort' => 2, 'color' => 'blue', 'shape' => 'circle', 'available' => false],
            '_1' => ['sort' => 1, 'color' => 'blue', 'shape' => 'circle'],
        ], iterator_to_array($result));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testLimit()
    {
        $this->bootstrap();
        $this->useDataA();
        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->limit(3, 3)
            ->eval();
        self::assertEquals([
            '_4' => ['sort' => 4, 'color' => 'purple', 'shape' => 'circle'],
            '_5' => ['sort' => 5, 'color' => 'purple', 'shape' => 'circle', 'available' => false],
            '_6' => ['sort' => 6, 'color' => 'purple', 'shape' => 'circle', 'available' => true],
        ], iterator_to_array($result));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testSortLimit()
    {
        $this->bootstrap();
        $this->useDataA();
        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->limit(3, 3)
            ->sortDesc( 'sort')
            ->eval();
        self::assertSame([
            '_6' => ['sort' => 6, 'color' => 'purple', 'shape' => 'circle', 'available' => true],
            '_5' => ['sort' => 5, 'color' => 'purple', 'shape' => 'circle', 'available' => false],
            '_4' => ['sort' => 4, 'color' => 'purple', 'shape' => 'circle'],
        ], iterator_to_array($result));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testBrackets()
    {
        $this->bootstrap();
        $this->useDataA();
        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->BRACKETS((new QueryPart())
                ->where('color')
                ->equals('purple')
                ->orWhere('color')
                ->equals('blue')
            )
            ->andWhere('available')
            ->equals(null)
            ->eval();
        self::assertEquals([
            '_1' => ['sort' => 1, 'color' => 'blue', 'shape' => 'circle'],
            '_4' => ['sort' => 4, 'color' => 'purple', 'shape' => 'circle'],
            '_7' => ['sort' => 7, 'color' => 'blue', 'shape' => 'rectangle'],
        ], iterator_to_array($result));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testOr()
    {
        $this->bootstrap();
        $this->useDataA();
        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->where('available')
            ->equals(null)
            ->OR((new QueryPart())
                ->where('color')
                ->equals('purple')
                ->orWhere('color')
                ->equals('blue')
            )
            ->andWhere('available')
            ->equals(true)
            ->eval();
        self::assertEquals([
            '_3' => ['sort' => 3, 'color' => 'blue', 'shape' => 'circle', 'available' => true],
            '_6' => ['sort' => 6, 'color' => 'purple', 'shape' => 'circle', 'available' => true],
            '_9' => ['sort' => 9, 'color' => 'blue', 'shape' => 'rectangle', 'available' => true],
            '_1' => ['sort' => 1, 'color' => 'blue', 'shape' => 'circle'],
            '_4' => ['sort' => 4, 'color' => 'purple', 'shape' => 'circle'],
            '_7' => ['sort' => 7, 'color' => 'blue', 'shape' => 'rectangle'],
        ], iterator_to_array($result));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testAnd()
    {
        $this->bootstrap();
        $this->useDataA();
        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->where('available')
            ->equals(null)
            ->AND((new QueryPart())
                ->where('color')
                ->equals('purple')
                ->orWhere('color')
                ->equals('blue')
            )
            ->eval();
        self::assertEquals([
            '_1' => ['sort' => 1, 'color' => 'blue', 'shape' => 'circle'],
            '_4' => ['sort' => 4, 'color' => 'purple', 'shape' => 'circle'],
            '_7' => ['sort' => 7, 'color' => 'blue', 'shape' => 'rectangle'],
        ], iterator_to_array($result));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testWhere()
    {
        $this->bootstrap();
        $this->useDataA();
        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->where('available')
            ->equals(null)
            ->eval();
        self::assertEquals([
            '_1' => ['sort' => 1, 'color' => 'blue', 'shape' => 'circle'],
            '_4' => ['sort' => 4, 'color' => 'purple', 'shape' => 'circle'],
            '_7' => ['sort' => 7, 'color' => 'blue', 'shape' => 'rectangle'],
        ], iterator_to_array($result));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testOrWhere()
    {
        $this->bootstrap();
        $this->useDataA();
        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->where('available')
            ->equals(null)
            ->orWhere('available')
            ->equals(true)
            ->eval();
        self::assertEquals([
            '_1' => ['sort' => 1, 'color' => 'blue', 'shape' => 'circle'],
            '_4' => ['sort' => 4, 'color' => 'purple', 'shape' => 'circle'],
            '_7' => ['sort' => 7, 'color' => 'blue', 'shape' => 'rectangle'],
            '_3' => ['sort' => 3, 'color' => 'blue', 'shape' => 'circle', 'available' => true],
            '_6' => ['sort' => 6, 'color' => 'purple', 'shape' => 'circle', 'available' => true],
            '_9' => ['sort' => 9, 'color' => 'blue', 'shape' => 'rectangle', 'available' => true],
        ], iterator_to_array($result));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testAndWhere()
    {
        $this->bootstrap();
        $this->useDataA();
        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->where('available')
            ->equals(null)
            ->andWhere('color')
            ->equals('purple')
            ->eval();
        self::assertEquals([
            '_4' => ['sort' => 4, 'color' => 'purple', 'shape' => 'circle'],
        ], iterator_to_array($result));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testAndBeforeOr()
    {
        $this->bootstrap();
        $this->useDataA();

        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->where('color')
            ->equals('purple')
            ->andWhere('available')
            ->equals(true)
            ->orWhere('shape')
            ->equals('circle')
            ->andWhere('color')
            ->equals('blue')
            ->eval();
        self::assertEquals([
            '_1' => ['sort' => 1, 'color' => 'blue', 'shape' => 'circle'],
            '_2' => ['sort' => 2, 'color' => 'blue', 'shape' => 'circle', 'available' => false],
            '_3' => ['sort' => 3, 'color' => 'blue', 'shape' => 'circle', 'available' => true],
            '_6' => ['sort' => 6, 'color' => 'purple', 'shape' => 'circle', 'available' => true],
        ], iterator_to_array($result));

        $result = di_get(QueryFactory::class)->select()
            ->from('A')
            ->where('color')
            ->equals('purple')
            ->orWhere('available')
            ->equals(true)
            ->andWhere('shape')
            ->equals('circle')
            ->orWhere('available')
            ->equals(false)
            ->eval();
        self::assertEquals([
            '_2' => ['sort' => 2, 'color' => 'blue', 'shape' => 'circle', 'available' => false],
            '_3' => ['sort' => 3, 'color' => 'blue', 'shape' => 'circle', 'available' => true],
            '_4' => ['sort' => 4, 'color' => 'purple', 'shape' => 'circle'],
            '_5' => ['sort' => 5, 'color' => 'purple', 'shape' => 'circle', 'available' => false],
            '_6' => ['sort' => 6, 'color' => 'purple', 'shape' => 'circle', 'available' => true],
            '_8' => ['sort' => 8, 'color' => 'blue', 'shape' => 'rectangle', 'available' => false],
        ], iterator_to_array($result));
    }
}
