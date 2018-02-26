<?php

namespace ItForFree\rusphp\Log;

/**
 * Общий родитель для разных логгеров
 * Несет в себе статический функционал, единый для всей системы
 */
abstract class SimpleLog
{
    /**
     * Активно ли логгирование 
     * (обратите внимание, что эта статическая переменная)
     * 
     * @var boolean 
     */
    protected static $log = true;
    
    /**
     * Вывод нужен в формате html или текстового файла?
     * @var boolean 
     */
    public static $inBrowserForHtml = true;
    
    
        /**
     * Переключение формата вывода лога
     * 
     * @param string $formatName позволяет в том числе определить 
     * способ переноса строк:
     *   возможные значения 1) text  2) html
     */
    public static function format($formatName)
    {
        if ($formatName == 'text') {
            self::$inBrowserForHtml = false;
        } elseif ($formatName == 'html') {
            self::$inBrowserForHtml = true; 
        } else {
            throw new Exception("Unknown logging format: $formatName");
        }
    }
    
    /**
     * Выключить логгирование
     * (по умолчанию включено)
     * 
     * -- выключение может потребоваться, 
     * если вы не хотите удалять инструкции лога  из кода (есть подозрение, 
     * что в будущем придётся использовать их же),
     * но вам надо запустить код в реальной среде, где логгирование запрещено.
     */
    public static function off()
    {
        self::$log = false;
    }
    
    /**
     * Включить логгирование 
     * (по умолчанию итак включено)
     */
    public static function on()
    {
        self::$log = true;
    }
    

}