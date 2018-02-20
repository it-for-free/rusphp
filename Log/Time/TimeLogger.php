<?php

namespace app\components\loggers;

/**
 * Класс для замера времени выполнения разных участков кода
 */
class TimeLogger {
    
    /**
     * Ассоциативный массив, где в качестве ключа указывается время 
     * 
     * @var array 
     */
    public static $times = [];
    
    
    /**
     * Порог значимости: Если интервал занял меньше этого времени (в секундах),
     *  то не будем показывать  его в результатах
     * 
     * @var float 
     */
    public static $maxUsefulLevel = 0.001;
    
    
    public static $roundAfterZeroTo = 4;
    
    /**
     * Запомнит время начала измерения (левая граница)
     * 
     * @param string $timeLabel  название измеряемого временного интервала (того, что происходит)
     */
    public static function start($timeLabel = 'noname')
    {
        static::$times[$timeLabel]['start'] = microtime(true);
    }
    
    /**
     * Запомнит время завершения измерения интервала (правая граница граница)
     * 
     * @param string $timeLabel  название измеряемого временного интервала (того, что происходит)
     */
    public static  function end($timeLabel = 'noname')
    {
        static::$times[$timeLabel]['end'] = microtime(true); 
    }
    
    /**
     * Возращает результат
     * 
     * @param boolean $includeIndefined -- нужно ли возвращать нули для значений, с которыми что-то нет так (например не задано начальное и конечное время), в противном случае будет брошено исключение
     * @return type
     */
    public static function getResults($includeUndefinedAsZero = false)
    {
        $results = [];
                
        if ($includeUndefinedAsZero) {
            foreach (static::$times as $timeLabel => $time) {
                if (!empty($time['end']) && !empty($time['start'])) {
                    $intervalTime = round(
                            $time['end'] - $time['start'],
                            static::$roundAfterZeroTo
                    );
                            
                    if ($intervalTime >= static::$maxUsefulLevel) {
                        $results[$timeLabel] = $intervalTime;
                    }
                } else {
                    $results[$timeLabel] = 0;
                }
            }
        } else {
            foreach (static::$times as $timeLabel => $time) {
                if (!empty($time['end']) && !empty($time['start'])) {
                    
                    $intervalTime = round(
                            $time['end'] - $time['start'],
                            static::$roundAfterZeroTo
                    );
                            
                    if ($intervalTime >= static::$maxUsefulLevel) {
                        $results[$timeLabel] = $intervalTime;
                    }
                } else {
                    throw new \Exception("Time boards hadnt set properly. Start: ["
                            . $time['start'] 
                            ."]  End ["
                            . $time['end'] . "]");
                }
            }
        }
        
        return $results;
    }
    
    
    /**
     * Получаем время для конктретного интервала (как результат -- число в секундах)
     * Вернёт даже нулевое время
     * 
     * @param string $intervalName имя уже записанного с помощью start() и end() интервала
     * @return float
     * @throws \Exception
     */
    public static function getIntervalTime($intervalName)
    {
        
        $result = 'unknow time';
        if (!empty(static::$times[$intervalName])) {
            $time = static::$times[$intervalName];
            if (!empty($time['end']) && !empty($time['start'])) {

                $result = round(
                        $time['end'] - $time['start'],
                        static::$roundAfterZeroTo
                );
                
            } else {
                throw new \Exception("Time boards hadnt set properly. Start: ["
                        . $time['start'] 
                        ."]  End ["
                        . $time['end'] . "]");
            }
        } else {
            throw new \Exception("TimeLogger -- undefined time interval!");
        }

        
        return $result;
    }
    
    

}
