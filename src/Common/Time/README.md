# `ItForFree/rusphp/Common/Time` (Пространство имен)

Содержит классы позвоялющие работать с временем/временными периодами.

##   `TimePeriod` Класс для обработки и форматирования временных периодов

### Метод `TimePeriod::changeTermFormatToStrict(int $termInMonth)` 
Выделит из срока, указанного только в месяцах(int) годы и месяцы.
Например: 17 месяцев -> 1 год 5 месяцев
```php
use ItForFree\rusphp\File\Common\Time;

TimePeriod::changeTermFormatToStrict(17);
```
где 17 - (int) - число месяцев. 

Вернёт ассоциативный массив с ключами 'years' и 'months'.
В нашем случае - такой:

```
[
    'years' => 1,
    'months' => 5,
]
```

### Метод `TimePeriod::termToString(int $yearsTerm, int $monthTerm)` 
Сформирует строку для вывода какого-либо срока в виде "N лет M месяцев"
В качестве аргументов метод принимает уже рассчитанное число месяцев и лет:
количество месяцев не может быть больше 11. Увеличьте количество лет.

```php
use ItForFree\rusphp\File\Common\Time;

TimePeriod::termToString(1, 5);
```
где 1 - количество лет, а 5 - количество месяцев

Вернёт строку "1 год 5 месяцев".

-- Осторожно) Метод может выбросить `LogicException()` в случае, если в качестве второго аргумента передано число > 11. 
Это значит, что Вам нужно выделить полное количество лет из месяцев. Можете использовать
метод TimePeriod::changeTermFormatToStrict()



##  Динамическое поределение интервала между запросами: `RequestsTimeInterval`

`RequestsTimeInterval` позволяет динамически менять изначально заданный интервал
 между запросами (напр. к какому-нибудь API) в зависимости от успешности завершения очередного запроса.