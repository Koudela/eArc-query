# eArc-query-language

```php
use eArc\QueryLanguage\Collector\QueryInitializer;
use eArc\QueryLanguage\Collector\QueryPart;
(new QueryInitializer())->from('')
->limit(0,0)
->sortAsc('')
->where('')
->notEqual('')
->andWhere('')
->geq('')
->orWhere('')
->gt('')
->AND((new QueryPart())->where('')->gt(''))
->OR((new QueryPart())->where('')->lt(''))
->JOIN('','')->ON('','')->where('')->equals('')
->eval();
```
