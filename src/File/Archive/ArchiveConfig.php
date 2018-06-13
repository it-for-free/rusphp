<?php

namespace rusphp\File\Archive;

use rusphp\OS\OSCommon as OS;
use rusphp\File\Path as Path;

/**
 * Для работы с архивами -- конфигурация и настройки
 */
class ArchiveConfig
{
    /**
     * Список расширений файлов типа "архив" (можно пополнять)
     * @var array 
     */
    public static $extentions = [
        'gz', 
        'zip',
        'rar'
    ];
   
}
