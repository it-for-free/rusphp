<?php

namespace ItForFree\rusphp\PHP\ArrayLib;

/**
 * Для работы с элементом массива
 */
class ArrayElement
{
    /**
     * Порядковый номер элемента ассоцитивного массива по его ключу
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
    
    
    /**
     * Вернет для первого совпадающего элемента массива его ключ
     * 
     * (используется нестрогое сравнение)
     * 
     * @param array $arr  массив, в котором искать
     * @param mixed $value  с чем сравнивать
     * @return mixed|null   ключ или null в случае неудачи (если не удалось ничего найти)
     */
    public static function getFirstKeyForValue($arr, $value)
    {
        $resultKey = null;
        foreach ($arr as $key => $val) {
           if ($value == $val) {
               $resultKey = $key;
               break;
           } 
        }
        
        return $resultKey;
    }
}