<?php

namespace ItForFree\rusphp\PHP\ArrayLib;

use ItForFree\rusphp\PHP\Comparator\Compare;

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
     * @param array $arr  массив, в котором искать
     * @param mixed $value  с чем сравнивать
     * @param bool $compareStrong  использовать ли строгое сравнение при поиске или нет
     * @return mixed|null   ключ или null в случае неудачи (если не удалось ничего найти)
     */
    public static function getFirstKeyForValue($arr, $value, $compareStrong = false)
    {
        $resultKey = null;
        foreach ($arr as $key => $val) {
           if (Compare::eq($value, $val, $compareStrong)) {
               $resultKey = $key;
               break;
           } 
        }
        
        return $resultKey;
    }
    
    
    /**
     * Вернет для первого совпадающего с переданным значением вложенного элемента
     * ключ первого уровня, по которому и лежит в массиве данный вложенный элемент.
     * 
     * @param array $arr  массив в котором ищем
     * @param mixed $value значение, которое ищем
     * @param \ItForFree\rusphp\PHP\ArrayLib\ArrNestedElement\ArrNestedElement $NestedElement описание вложенного элемента
     * @return mixed|null  в случае если ключ не обнаружен, возвращем null @see http://fkn.ktu10.com/?q=node/10810
     */
    public static function getFirstKeyForNestedElementValue($arr, $value, $NestedElement)
    {
        $result  = null;
        foreach ($arr as $key => $subArray) {
           $nestedValue  = $NestedElement->get($subArray);
           if (Compare::eq($nestedValue, $value, $NestedElement->isCompareStrong())) {
              $result  = $key; 
              break;
           }
        }
        return $result;
    }
    
    /**
     * Вернет первый элемент массива
     * 
     * (работает, начиная с php5.4)
     * 
     * @param array $arr
     * @return mixed значение первого элемента, в случае если его нет - null.
     */
    public static function getFirst($arr)
    {

        if (function_exists('array_key_first')) { // начиная с php 7.3
            return $arr[array_key_first($arr)];
        } else {  // начиная с php 5.4
            $values = array_values($arr);
            if (isset($values[0])) {
                return  $values[0];
            }
        }
        
        return null;
    }
}