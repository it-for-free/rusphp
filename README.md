# rusphp

Набор php-классов (и их методов)

```php
composer require it-for-free/rusphp
```


## Использование

О способах использования (*установки*) [читайте здесь](http://fkn.ktu10.com/node/8592).

Версии и совместимость с PHP:
* Версии `v1.*` -- для поддержки кода php5.5 и старше (например, для `array()` вместо `[]`),
   эти же версии кода попадают в остальные ветки, но в `1.*` можно найти (добавить) код, адоптированный под старые версии.
* Остальные версии ориентированы на работу в `php7` и выше.

## Цели и задачи библиотеки

Писать сюда функции общего назначения, которые можно было бы использовать в разных проектах 
-- как минимум это касается удобных функций-обёрток (как `ItForFree\rusphp\Log\SimpleEchoLog.php`), 
как максимум -- нового полезного функционала для специфических областей (`ItForFree/rusphp/Html/Table/ArrayRebuilder.php`)

## Документация

### Дополнения к общим возможностям `PHP`:

* [Массивы](src/PHP/ArrayLib/README.md)
* [Функции обртного вызова (callable)](src/PHP/Callback/README.md) 
* [Сравнение значений](src/PHP/Comparator/README.md)
* [Сессии](src/PHP/Session/README.md)
* [Регулярные выражения](src/PHP/Regexp/README.md)  

### Прочий функционал

* [Логгирование (журналирования для отладки)](docs/logging.md)
* [Работа с **изображениями** (обрезка изображений "на лету")](src/File/Image/README.md)
* C SSH соединением
* Архивами
* Измерение используемой оперативной памяти
* URL: `ItForFree\rusphp\Network\Url` позволяет удобно работать с URL (адресами ссылок)
* [Создание файла c исходным кодом проекта](src/Documentaion/FileCreator/README.md)
* [Работа с телефонными номерами](src/Common/Phone/PhoneNumber/README.md)
* [Безопасноть (в частности секрентые ключи/токены)](src/PHP/Security/README.md)
* [Работа с онлайн-картами (яндекс и google maps)](src/Common/Map/README.md)
* [Для работы с доменами](src/Network/Domain/README.md)
* Для работы с **временем**:
   - [Работа со временем вообще](src/Common/Time/README.md)
   - [Замер времени выполнения фрагментов кода](src/Log/Time/README.md)


### UI Работа с  пользовательским интерфейсом

Используйте классы пространcтва `ItForFree\rusphp\Common\Ui`:

* [Хлебные крошки (breadcrumbs)](src/Common/Ui/Breadcrumbs/README.md)
* [Сообщения/уведомления](src/Common/Ui/Message/README.md)

## Автоматическое тестирование

* Тестирование [с помощью codeception](docs/codeception.md)

Запуск тестов:
```shell
cept run unit
```


## @ToDo

* Выяснить ситуацию с обновлением `ralouphie/mimey` до PHP8 https://packagist.org/packages/ralouphie/mimey 
   и вернуться на него обратно с временного `jmoati/mimey`.

## Потомки rusphp ;) (вынесено в отделные пакеты)

* Web-клиенты к различным системам: https://github.com/it-for-free/php-web-clients


