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
     * Вернёт фрагмент md5 хэша заданной длинны (начиная с начала)
     * 
     * @param srting $str  исходная строка
     * @param int $length  длина результата
     * @return string
     */
    public static function md5($str, $length = 10)
    {
        return substr(md5($str), 0, $length);
    }
}
