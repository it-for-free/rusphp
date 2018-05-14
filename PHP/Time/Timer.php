<?php

namespace ItForFree\rusphp\PHP\Time;

use ItForFree\rusphp\PHP\Str\StrCommon as Str;
use ItForFree\rusphp\Log\SimpleEchoLog as log;

/**
 * @deprecated since version 2.0.2  Используйте  ItForFree\Log\Time\Timer
 * 
 * Для того, чтобы замерять время
 */
class Timer
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
     * До какого знака округлять (чтобы не таскать бесконечные дроби)
     * 
     * @var int
     */
    public static $roundPow =  4;
   
   /**
    * Начнем измерять время для интервала
    * 
    * @param string $key -- уникальный (в рамках скрипта) ключ для данного интеревала (важно если за один запуск измеряются несколько разных интервалов)
    * @return boolean
    */
   public static function start($key = 'default')
   {
       self::$times[$key] = microtime(true);
       return true;
   }
   
    /**
     * Завершит измерение и вернёт длительность интервала (в микросекундах)
     * 
     * @param  string $key  -- уникальный (в рамках скрипта) ключ для данного интеревала (важно если за один запуск измеряются несколько разных интервалов)
     * @return float
     */
    public static function end($key = 'default')
    {
        if (!empty(self::$times[$key])) {
            $endTime = microtime(true);
            $timeInterval = round($endTime -  self::$times[$key], self::$roundPow);
        } else {
           throw new \Exception(' You should previously call Timer::start($key) method! ');
        }
        
        return $timeInterval;
    }
    
    /**
     * @deprecated since version 2.0.0 Испольльзуйте SimpleEchoLog или иную систему логгирования отдельно
     * 
     * printm Распечатает результат для отрезка
     * 
     * @param string $key      имя отрезка изменения
     * @param string $comment  (необзятельно) дополнительный комментарий
     */
    public static function pme($key = 'default', $comment = 'Время выполнения скрипта: ')
    {
        if ($key != 'default') {
            $keytext = "[$key]";
        } else {
            $keytext = '';
        }
        
        log::me("$message $keytext: " . self::end($key) . ' сек.');
    }
}