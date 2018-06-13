<?php

namespace ItForFree\rusphp\Log;

/**
 * @todo доделать
 * 
 * Простое логгирование в файл с возможностью его очистки 
 */
class SimpleFileLog extends SimpleLog
{
    
    /**
     * Символ или набор символов для новой строки
     * 
     * @var string
     */
    public static $newLine = "\n";
    
    /**
     * Путь к файлу, в который пишется журнал
     * @var string 
     */
    public static $filePath = null;
    
    
    
    public static function me() 
    {
        throw new \Exception('No realization of me() here!');
    }
  
    
}