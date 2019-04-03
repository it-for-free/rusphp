<?php

namespace ItForFree\rusphp\PHP\ArrayLib;

use ItForFree\rusphp\PHP\Str\StrCommon as Str;
use ItForFree\rusphp\Log\SimpleEchoLog as log;

/**
 * Для фильтрации массивов
 */
class ArrFilter
{
    
    /**
     * Удалит из массива указанные значения 
     * (с сохранением ключей для оставшихся)
     * 
     * @param array $arr   исходный массив
     * @param array $values значения, которые в нем надо удалить
     * @return array 
     */
    public static function removeValues($arr, $values)
    {
        foreach ($arr as $key => $val) {
            if (in_array($val, $values)) {
                unset($arr[$key]);
            }
        }
        return $arr;
    }
    
    /**
     * Оставит в массиве только переданные значения (сохранив ключи)
     * 
     * @param array $arr     исходный массив
     * @param array $values  те значения, что нужно оставить
     * @return array
     */
    public static function getOnly($arr, $values)
    {
        foreach ($arr as $key => $val) {
            if (!in_array($val, $values)) {
                unset($arr[$key]);
            }
        }
        return $arr;
    }
}