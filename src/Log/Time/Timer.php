<?php

namespace ItForFree\rusphp\Log\Time;

use ItForFree\rusphp\Log\SimpleEchoLog;


/**
 * Класс для замера времени выполнения разных участков кода
 */
class Timer extends \ItForFree\rusphp\Log\SimpleLog {
    
    /**
     * Ассоциативный массив, где в качестве ключа указывается время 
     * 
     * @var array 
     */
    protected static $times = [];
    
    
    /**
     * Порог значимости: Если интервал занял меньше этого времени (в секундах),
     *  то не будем показывать  его в результатах
     * 
     * @var float 
     */
    public static $maxUsefulLevel = 0.001;
    
    /**
     * Число отображаемых знаков
     * 
     * @var int
     */
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
        if (empty(static::$times[$timeLabel]['start'])) {
            throw new \Exception('Start time not set!');
        } else {
            static::$times[$timeLabel]['end'] = microtime(true); 
        }
    }
    
    /**
     * Возращает результат
     * 
     * @param boolean $includeUndefinedAsZero -- нужно ли возвращать нули для значений, с которыми что-то нет так (например не задано начальное и конечное время), в противном случае будет брошено исключение
     * @return array
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
    public static function getIntervalTime($intervalName = 'noname')
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
    
    /**
     * Обёртка над self::getIntervalTime($intervalName = 'noname')
     * 
     * Распечатает время для конктретного интервала (как результат -- число в секундах)
     * Вернёт даже нулевое время
     * 
     * @param string $intervalName имя уже записанного с помощью start() и end() интервала
     * @return float    секунды
     * @throws \Exception
     */
    public static function me($intervalName = 'noname')
    {
        $time = self::getIntervalTime($intervalName);
        SimpleEchoLog::me($time, $intervalName);
    }
	
	/**
     * Завершит интервал и вернет время (как результат -- число в секундах)
     * Вернёт даже нулевое время
	 * 
     * @param string $intervalName имя уже записанного с помощью start() и end() интервала
     * @return float    секунды
     * @throws \Exception
     */
    public static function get($intervalName = 'noname')
    {
		self::end($intervalName);
        return self::getIntervalTime($intervalName);
    }
	
}
