<?php

namespace ItForFree\rusphp\PHP\Str;

use ItForFree\rusphp\PHP\Regexp\RegexpExamples;

/**
 * Готовая валидация для некоторых типов полей
 *
 */
class Validator {
    /**
     * Проверка на собственное имя человека 
     * 
     * @param srting $str
     * @return boolean
     */
    public static function isHumanName($str)
    {
        return !empty(preg_match(RegexpExamples::humanName, $str));
    }
}
