<?php

namespace ItForFree\rusphp\Testing\Tester;

/**
 * Description of TestUser
 */
class Tester
{

    /**
     * Добавит в конец строку с датой и времененем
     * 
     * @param string $str строка, которую надо дополнить временем и датой
     * @return string
     */
    public static function addDatetime($str)
    {

        $date = new \DateTime();

        return $str . $date->format('Y-m-d H:i:s');
    }

    /**
     * Добавит в конец строку с датой и временем, записанною слитно (через тире)
     * 
     * @param string $str строка, которую надо дополнить временем и датой
     * @return string
     */
    public static function addDatetimeTogether($str)
    {

        return $str . static::getDatetimeTogetherStr();
    }

    public static function getDatetimeTogetherStr()
    {
        $date = new \DateTime();

        return $date->format('Y-m-d-H-i-s');
    }

    /**
     * http url из текущих дат и времени
     * 
     * @return string
     */
    public static function getUrlOfHttpByDatetime()
    {
        return 'http://' . static::getDatetimeTogetherStr() . '.test';
    }

}
