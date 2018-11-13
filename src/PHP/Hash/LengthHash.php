<?php

namespace ItForFree\rusphp\PHP\Hash;

/**
 * Короткий (заданной длины) хеш из строки
 * 
 * (php short hash from string -- with length)
 * 
 * @author qwe
 */
class LengthHash 
{
    /**
     * 
     * @param type $str
     * @param type $length
     */
    public static function md5($str, $length = 10)
    {
        substr(md5($str), 0, $length);
    }
}
