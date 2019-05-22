<?php

namespace ItForFree\rusphp\PHP\Regexp;

/**
 * Обертки для работы с регулярными выражениями
 */
class Regexp {
   
    /**
     * Проверит соответствует ли строка хотя бы одному из регулярных выражений
     * @param string   $str     cтрока, которую надо проверить
     * @param string[] $patterns массив регулярных выражений
     * @return boolean
     */
    public static function isLikeAny($str, $patterns)
    {
        $result = false;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $str)) {
                $result = true;
                break;
            }
        }
        return $result;
    }
}
