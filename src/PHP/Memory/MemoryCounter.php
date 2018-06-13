<?php

namespace rusphp\PHP\Memory;

use rusphp\PHP\Str\StrCommon as Str;
use rusphp\Log\SimpleEchoLog as log;
use rusphp\Measure\Information as Info;

/**
 * Для измерения используемой оперативной памяти
 */
class MemoryCounter
{
   /**
    * Для того чтобы можно было мерить разные участки кода 
    * метка начала интервала будет записываться в данный массив по ключу,
    * а потом извлекаться с помощью методом, который вычисляет длину интервала
    * 
    * @var array
    */
    private static $times = [
       'default' => 0 // ключ по умолчанию
    ]; 
   
    
    /**
     * В чём измерять (для вывода) 
     * @see возможные коды в rusphp\Measure\Information::$conventionLetters
     * 
     * @var string 
     */
    public static $measure = 'M'; 


    /**
    * Получим начальное значение занимаемой памяти
    * 
    * @param string $key -- уникальный (в рамках скрипта) ключ для данного интеревала (важно если за один запуск измеряются несколько разных интервалов)
    * @return boolean
    */
   public static function startCount($key = 'default')
   {
       self::$times[$key] = memory_get_usage();
       return true;
   }
   
    /**
     * Вернёт разницу объёма используемой памяти между началом измерения и концом
     * 
     * @param  string $key  -- уникальный (в рамках скрипта) ключ для данного интеревала (важно если за один запуск измеряются несколько разных интервалов)
     * @return float
     */
    public static function endCount($key = 'default')
    {
        if (!empty(self::$times[$key])) {
            $endMemory = memory_get_usage();
            $Memory = $endMemory -  self::$times[$key];
        } else {
           throw new \Exception(" You should previously call MemoryCounter::startCount() method  -- your key [$key] is undefined! ");
        }
        
        return $Memory;
    }
    
    /**
     * Псевдоним для self::endCount()
     * Вернёт разницу объёма используемой памяти между началом измерения и концом (в байтах)
     * 
     * @param  string $key  -- уникальный (в рамках скрипта) ключ для данного интеревала (важно если за один запуск измеряются несколько разных интервалов)
     * @return float
     */
    public static function сount($key = 'default')
    {  
        return self::endCount($key);
    }
    
    /**
     * Сообщение с завершением измерения
     * 
     * @param string $message пользовательское сообщение
     * @param string $key     уникальный в рамках скрипта ключ
     */
    public static function me($message = 'Объем используемой памяти ', $key = 'default')
    {  
        if ($key != 'default') {
            $keytext = "[$key]";
        } else {
            $keytext = '';
        }
        
        log::me("$message $keytext: " . self::t(self::endCount($key)));
    }
    
    /**
     * Сообщение о пиковой выделенной памяти
     * 
     * @param string $message
     */
    public static function meMax($message = 'Максимальный выделенный объём  используемой памяти ')
    {  
        log::me("$message : " . self::t(self::peak()));
    }
    
    /**
     * Пиковое значение используемой скриптов оперативной памяти за всё предыдущее время работы (до вызова)
     */
    public static function peak()
    {  
        return memory_get_peak_usage();
    }
    
    
    /**
     * Возвращает максимальную доступную скрипту оперативную память
     * (просто из php.ini средствами ini_get('memory_limit') )
     * 
     * @param  boolean $inBytes  если true, то вернёт значение в байтах
     * @return mixed  -- число или строка в зависимости от переданных значений
     */
    public static function limit($inBytes = false)
    {
        $result  = ini_get('memory_limit');
        if ($inBytes) {
            $result = Info::fromString($result);
        }
        return $result;
    }
    
    /**
     * Сообщение о пиковой выделенной памяти
     * 
     * @param string $message
     */
    public static function meLimit($message = 'Формально доступный объём оперативной памяти (php.ini): ')
    {  
       // log::me(self::limit());
        log::me($message  . self::t(self::limit(true)));
    }
    
    /**
     * Для вывода числа в нужном формате (Килобайты мегабайты и т.д.)
     * 
     * @param int $bytes число байт
     * @return string  -- число вместе с буквой-идентификатором 
     */
    public static function t($bytes) 
    {
        return Info::convert($bytes, self::$measure) . self::$measure;
    }
    
}