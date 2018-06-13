<?php

namespace ItForFree\rusphp\Log\Json;

/**
 * Предоставляет глобальный массив для хранения значений
 * - умеет распечатывать в json
 * 
 * @todo по сути это просто статический логгер -- надо понять что с ним делать.
 */
class AjaxLogger {
    
    /**
     * Ассоциативный массив, где в качестве ключа указывается имя для какого-то значения-
     * именно его надо прикрепить к ajax-ответу 
     * 
     * @var array 
     */
    public static $logs = [];
    
    
    /**
     * Добавляем запись в журнал
     * 
     * @param string $key
     * @param string $value
     */
    public static function add($key, $value)
    {
        if (isset(static::$logs[$key])) {
            static::$logs[$key] .= $value;
        } else {
            static::$logs[$key] = $value; 
        }
    }
    
    
    /**
     * Добавляем запись в журнал (с  распечаткой объекта или массива)
     *
     * @param string $key
     * @param array|object $ObjectOrArray -- то что надо распечатать
     */
    public static function addAndPrint($key, $ObjectOrArray)
    {
        if (isset(static::$logs[$key])) {
            static::$logs[$key] .= json_encode($ObjectOrArray); ;
        } else {
            static::$logs[$key] = json_encode($ObjectOrArray, true); ; 
        }    
    }
    
    /**
     * Возращает результат
     * 
     * @return array
     */
    public static function getResults()
    {
        return static::$logs;
    }

}
