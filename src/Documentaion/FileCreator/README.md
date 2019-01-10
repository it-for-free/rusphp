

**ВНИМАНИЕ:**  в данный момент имеется отдельный репозиторий с более новой версией кода: 
https://github.com/kalyashka/srcdoc


# Устаревшная документация: Source Code File Creator  
## по-русски

Скрпт `Source Code File Creator ` позволяет создавать html файл 
(потом его можно руками конвертировать во что угодна, например .docx)
со всем исходным кодом вашего проекта.

Пример вызова из корня вашего проекта (после установки `rusphp` средствами composer):

```shell
php vendor/bin/create-source-code-file.php --exclude="vendor;web/assets;uploaded;libs;compile" --out="source.html"
```
ВНИМАНИЕ: не забудьте перенести файл с исходным кодом из корня проекта (скорее всего, он там не нужен).