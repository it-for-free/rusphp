<?php

namespace ItForFree\rusphp\PHP\DateTime;


/**
 * Дата и время
 * Для измерения дистанции между двумя датами
 * -- обёртка над \DateTime
 * 
 * Полезно посмотреть:
 * @see https://secure.php.net/manual/ru/datetime.diff.php#97880
 */
class DateTimeDifference
{
 
    /**
     * Вычислит число лет прошедших к сегодняшнему моменту начиная с даты,
     * хранящейся в $startDateTime
     * (можно использовать для вычисления возраста по дате рождения)
     * 
     * @param \DateTime $startDateTime  точка времени (подразумевается, что в прошлом)
     * @return int число прошедших лет
     */
    public static function yearsByCurrentMoment($startDateTime)
    {
        $tz  = new \DateTimeZone('Europe/Brussels'); // зона не имеет значения в данном случае
        $years = $startDateTime
                ->diff(new \DateTime('now', $tz)) 
                ->y;
     
        return $years;
    }
    
    /**
     * Вычислит число дней прошедших к сегодняшнему моменту начиная с даты,
     * хранящейся в $startDateTime
     * (можно использовать для вычисления возраста по дате рождения)
     * 
     * @param \DateTime $startDateTime  точка времени (подразумевается, что в прошлом)
     * @return int число прошедших дней
     */
    public static function daysByCurrentMoment($startDateTime)
    {
        $tz  = new \DateTimeZone('Europe/Brussels'); // зона не имеет значения в данном случае
        $days = $startDateTime
                ->diff(new \DateTime('now', $tz)) 
                ->days;
     
        return $days;
    }
    
    /**
     * Разница в днях между двумя датами
     * 
     * @param \DateTime $startDateTime начальная дата
     * @param \DateTime $endDateTime   конечная дата
     * @return int                     число дней
     */
    public static function inDays($startDateTime, $endDateTime)
    {
        $days  = $startDateTime->diff($endDateTime)->days;
        return $days;
    }               
}