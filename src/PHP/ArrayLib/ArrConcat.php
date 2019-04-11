<?php

namespace ItForFree\rusphp\PHP\ArrayLib;

use ItForFree\rusphp\PHP\Str\Str;

/**
 * Конкатенация элементов масcива (склеивание в строку)
 */
class ArrConcat
{
    /**
     * Конкатенирует те эелменты массива, которые не являются пустыми строками
     * 
     * [[concat not empty string array values]]
     * 
     * @param string $glue   соединительный символ или строка
     * @param array $arr    массив, непустые (как строки) элементы которого нужно соединить (сконкатенировать)
     * @return string
     */
    public static function notSpaceOnlyStr($glue, $arr)
    {
        foreach ($arr as $key => $value) {
            if (Str::isSpaceOnly($value)) {
                unset($arr[$key]); 
            }
        }
        return implode($glue, $arr);
    }
}