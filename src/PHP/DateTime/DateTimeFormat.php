<?php

namespace ItForFree\rusphp\PHP\DateTime;


/**
 * Дяя получения времени и даты в другом в другом формате
 * (в частности перевода строки времени и даты из одорго формата в другой)
 * 
 * дата и время
 */
class DateTimeFormat
{
    
    /**
     * Изменит формат строки даты  (дата и время)
     * 
     * @param string $dateTimeStr строка формата
     * @param string $oldFormat   старый формат (как для \DateTime)
     * @param string $newFormat   новый формат (как для \DateTime)
     * @return string строка даты в новом формате или пустая строка в случае неудачи
     */
    public static function get($dateTimeStr, 
        $oldFormat = 'Y-m-d H:i:s', $newFormat = 'd.m.Y')
    {
        $result = '';
        $DateTime = \DateTime::createFromFormat($oldFormat, $dateTimeStr);
        if ($DateTime) {
            $result = $DateTime->format($newFormat);
        }
        return $result;
    }
}