<?php

namespace ItForFree\rusphp\PHP\ArrayLib;

use ItForFree\rusphp\PHP\Comparator\Compare;
use ItForFree\rusphp\PHP\ArrayLib\ArrNestedElement\ArrNestedElement;

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
    public function getFirstKeyForNestedElementValue($arr, $value, $NestedElement)
    {
        $result  = null;
        foreach ($arr as $key => $subArray) {
           $nestedValue  = $NestedElement->get($subArray);
           if (Compare::eq($nestedValue, $value, $this->strongCompare)) {
              $result  = $key; 
              break;
           }
        }
        return $result;
    }
}