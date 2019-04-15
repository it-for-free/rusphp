# Сессии

Тут содержатся классы в том числе для уменьшения времtни блокировки
 при записи в стандартное файловое хранилище сессий php.

* `Session` базовый класс для работы с сессией.
* `Event` класс для работы с событиями (история этих событий хранится в сессии),
     позволяет, например, узнать открывалась ли данная страница пользователем
     в течении текущей сессии или нет.


##  `Event`


Пример использования в смарти (опеределяем выводили ли мы этот блок уже или нет):

```php

{php} 
$this->assign('popupFirstTime', 
    \ItForFree\rusphp\PHP\Session\Event::isFirstTime('welcome-popup'));
{/php}

{if $popupFirstTime}
    <div style="display: none;"id="welcome">
       ......
    </div>

    <script type="text/javascript">
        {literal}
        jQuery(document).ready(function () {
            setTimeout(function () {
                $.fancybox.open({
                    src:  '#welcome',
                    type: 'inline',
                });
            }, 800);
        });
        {/literal}
    </script>
{/if}

```

