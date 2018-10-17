# Онлайн-карты

## Расчет координат центра дял набора точек и значения приближения (zoom)

Используйте MapConfigurator для рассчета зума и центра онлайн карт (гугл, яндекс), далее эти значения 
можно передать в JS.

Получение сразу всех доступных настроек по переданному массиву координат можно провести так:

```php
use ItForFree\rusphp\Common\Map\MapConfigurator;

$MapConfig = new MapConfigurator($coords);
$res = $MapConfig->getAllSettings();
```


 

