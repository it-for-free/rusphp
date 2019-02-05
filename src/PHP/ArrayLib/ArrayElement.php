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
    
    /**
     * Вернет случайный элемент массива, 
     * работает на основе стандартной array_rand()
     * 
     * ((php get random element from array))
     * 
     * @param mixed[] $arr  массив значений произвольного типа
     * @return mixed    случайно выбранный элмент массива
     */
    public static function getRandom($arr)
    {
        return $arr[array_rand($arr, 1)];
    }
}