[it-for-free/rusphp: вернуться к оглавлению](../README.md)

# Дополнения к codeception

Примеры работыс codeception на русском: http://fkn.ktu10.com/?q=node/9956


## IFFAcceptanceTester
Для приёмочных тестов имеется базовый класс  `ItForFree\rusphp\Testing\Codeception\Acceptance\IFFAcceptanceTester` -- 
наследуйте свои  классыы от него, например: 
```php
<?php
namespace Step\Acceptance;

/**
 * Класс специфичный для тестового пользователя
 */
class TestUser extends \ItForFree\rusphp\Testing\Codeception\Acceptance\IFFAcceptanceTester
{

}
```

и далее:
```php
$I = new TestUser($scenario);
```

Позволяет в частности удобно записывать в консоль дополнительную инфромацию (logging):
```php
$I->log('Im here!');
 ```
-- обладает возможностью выводить данные в консоль разными цветами (linux), используя 
функционал symfony/console: https://symfony.com/doc/current/console/coloring.html

