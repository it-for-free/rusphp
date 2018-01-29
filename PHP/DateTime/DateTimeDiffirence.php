<?php

namespace ItForFree\rusphp\PHP\DateTime;


/**
 * Для измерения дистанции между двумя датами
 * -- обёртка над \DateTime
 * 
 * Полезно посмотреть:
 * @see https://secure.php.net/manual/ru/datetime.diff.php#97880
 */
class DateTimeDifference
{
 
    /**
     * Вычислит число лет прошедших к сегодняшнему моенту начиная с даты,
     * хранящейся в $fromDateTime
     * (можно использовать для вычисления возраста по дате рождения)
     * 
     * @param \DateTime $$startDateTime  точка времени (подразумевается, что в прошлом)
     * @return int число прошедших лет
     */
    public static function yearsByCurrentMoment($startDateTime)
    {
        $tz  = new DateTimeZone('Europe/Brussels'); // зона не имеет значения в данном случае
        $years = $startDateTime
                ->diff(new DateTime('now', $tz)) 
                ->y;
     
        return $years;
    }
    
    
    /**
     * Разница в днях между двумя датами
     * 
     * @param \DateTime $startDateTime
     * @param \DateTime $endDateTime
     * @return int                     число дней
     */
    public static function inDays($startDateTime, $endDateTime)
    {
        $days  = $startDateTime->diff($endDateTime)->d;
        return $days;
    }
                   
}