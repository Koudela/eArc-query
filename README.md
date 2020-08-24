# eArc-query-language

This is the lightweight standalone (zero dependencies) find/select query language 
component of the eArc framework.

Its full type hint support and its sql-like syntax make it easy to learn and 
easy to use. 

You can bind this component to any database or index that supports some kind of
categories (e.g. tables) and is able to resolve the following relations:
* =
* !=
* <
* <=
* &gt;
* &gt;=
* IN
* NOT IN

You may use only a subset of the relations or only one single category if you
like.
 
 ## installation
 
Install the earc dependency injection library by using composer.
 
 ```
 $ composer require earc/query-language
 ```

## binding

To bind the language to your database/index implement the `ResolverInterface`. 
If you use the `AbstractResolver` only three methods need implementation: 
`findAll()`, `queryRelation()` and `sort()`. 

```php
use eArc\QueryLanguage\AbstractResolver;

class MyResolver extends AbstractResolver
{
    public function findAll(string $dataCategory) : iterable
    {
        // ...
    }

    protected function queryRelation(string $dataCategory, string $dataProperty, string $cmp, $value, ?iterable $allowedDataIdentifiers = null) : iterable
    {
        // ...
    }

    public function sort(string $dataCategory, string $sort, iterable $dataPropertyNames, ?iterable $dataIdentifiers, ?iterable $allowedDataIdentifiers = null, int $limit = 0, int $offset = 0) : iterable
    {
        // ... You may skip this and throw an exception if you do not want to sort your results via query.
    }
}
```

The returned iterable has to use for the keys unique data identifier strings and 
as values the data items.

HINT: The unique data identifiers have to contain one non integer character, 
otherwise PHP would transform the string key to an integer. 

You may use several distinct bindings in your project. That is why the resolver
has to be bound to your query factory. Just extend the `AbstractQueryFactory`
and implement the `getQueryResolver()` method. 

```php
use eArc\QueryLanguage\AbstractQueryFactory;
use eArc\QueryLanguage\Interfaces\ResolverInterface;

class MyFinder extends AbstractQueryFactory
{
    protected function getQueryResolver() : ResolverInterface
    {
        // ...
    }
}
```

To inject your resolver you can use traditional constructor injection or use 
some more sophisticated tool like [earc/di](https://github.com/Koudela/eArc-di/).

## usage

### simple usage

The simple usage has been inspired by the `findBy()` method of the doctrine 
repositories.

```php
$qFactory = new MyFinder();
$result = $qFactory->findBy('SomeCategory', []);
```

This gives all data entries of the data category `SomeCategory`.

```php
$qFactory = new MyFinder();
$result = $qFactory->findBy('Wallpaper', ['color' => 'green', 'available' => true]);
```

This gives all data entries of the data category `Wallpaper` having the value
`green` for the property `color` and `true` for the property `available`.

Internally this resolves to a Query...

```php
(new QueryInitializer($qFactory->getQueryResolver()))
    ->from('Wallpaper')
    ->where('color')->equals('green')
    ->andWhere('available')->equals(true)
    ->eval();
```

### query based usage

You can use the query language directly by using `find()` instead of `findBy()`.
`find()` does return a `QueryInitializer` you can build your query from.

```php
$qFactory = new MyFinder();
$result = $qFactory->find()
    ->from('SomeCategory')
```

The first query part is always `from`. Here you specify the data category.

You can specify an optional `limit` as second query part.

```php
    ->limit($limit, $offset)
```

The `$limit` is an `int` and specifies the maximal number of returned data items.
The `$offset` is an `int` too and specifies the position of the first item.
`limit` is especially handy for all kinds of pagination.

The third query part is optional too. It specifies the sorting of the returned
data items. Of course `limit` is applied after sorting.

```php
    ->sortAsc('color', 'price')
    // or
    ->sortDesc('color', 'price')
```

You may pass as many properties as you wish. The data items will be sorted by the 
first property, if this is equal for some items they will be sorted by the second
property and so on.

HINT: Sort has to be implemented in the resolver. Its implementation may differ
from the behaviour explained here.

After this there is always either an `eval` or a `where` expression. `eval` 
would return at this point all data items of the data category specified by 
`from`  (only influenced by the optional `limit` and `sort`). Whereas `where` 
introduces a relation which may thin out the data items of the result. It takes
a property name as argument.

```php
    ->where('price')
```

The `where` expression has to be chained to one of the following relations:

```php
    ->equals($value)
    ->notEqual($value)
    ->lt($value)
    ->leq($value)
    ->gt($value)
    ->geq($value)
    ->in($iterable)
    ->notIn($iterable)
```

This expression can be evaluated via `eval()` or chained by a second `where` and
a logical conjunction prefix:

```php
    ->andWhere('property')
    // or
    ->orWhere('property')
```

These are followed by another relation and will be evaluated via `eval()` or 
chained by another `andWhere()` or `orWhere` and so on.

If the `AbstractResolver` is used by the resolver `andWhere()` conjunctions will 
be evaluated before `orWhere()` conjunctions.

In case you need another behaviour you can use the `BRACKETS()` method instead
of `where()`, `AND()` instead of `andWhere()` and `OR()` instead of `orWhere()`. 
They take a query expression as argument which is evaluated first and in the 
case of `AND()` and `OR()` thereafter applied to the result of the expression on
the left.

```php
use eArc\QueryLanguage\Collector\QueryPart;

    ->BRACKETS((new QueryPart())->where('property')...)
    ->AND((new QueryPart())->where('property')...)
    ->OR((new QueryPart())->where('property')...)
```

You can use the same instance of `QueryPart` for all your bracket expressions.
(It is an object without properties.)

## the result

If the resolver uses the `AbstractResolver` `eval()` returns a `QueryResult`. 
To retrieve the data items you have to iterate over it.

### restrict the result

Both `find()` and `findBy()` take as last argument an `iterable` 
`$allowedDataIdentifiers` which restrict the returned result to the data items
pointed at by the allowed data identifiers.

## releases

### release 0.0
- the first official release
