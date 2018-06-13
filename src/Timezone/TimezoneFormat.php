<?php

namespace ItForFree\rusphp\Timezone;

/**
 * Для вывода таймзоны определённом виде
 * временная зона, часовой пояс
 */
class TimezoneFormat
{
    
    /**
     * Выведет на экран смещение временной зоны в формате:
     * +hhmm или 
     * -hhmm
     * @see (функция не закончена, ориентирована на часы)
     * Например для Мосевы надо передать 3 и получим:
     * +0300
     * 
     * @param часы $hours
     * @param минуты $minutes
     * @return string
     */
    public static function getOffsetString($hours = 0, $minutes = 0) {
        $sign = '+';
        if ($hours < 0) {
            $sign = '-';
        }
        return $sign
                . sprintf('%02d', $hours)
                . sprintf('%02d', $minutes);
    }
}