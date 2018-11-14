<?php

namespace ItForFree\rusphp\PHP\ArrayLib;

/**
 * Для работы с элементом массива
 */
class ArrayElement
{
    /**
     * Порядковый номер элмента ассоцитивного массива по его ключу
     * 
     * @param array $arrayKeys
     * @return mixed   int or FALSE
     */
    public static function getOrderNumberByKey($arr, $key)
    {
        return array_search($key, array_keys($arr));
    }
}