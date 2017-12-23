<?php

namespace ItForFree\rusphp\PHP\ArrayLib;


/**
 * Класс (методы) Изменяет структуру массива или извлекает данные в указанном виде --
 * для работы со сложными вложенными массивами.
 * 
 * (напр. разворачивание в одномерный массив значений многомерного)
 */
class Structure
{   
    /**
     * Все значения будут выстроены в линию (одномерный массив)
     * с "заходом" во вложенные массивы
     * (рекурсивная функция)
     * 
     * @param  array $nestedArray   массив, которые может содержать вложенные массивы
     * @return array
     */
    public static function getAllValuesAsOneDemisionalArray($nestedArray)
    {
        $result = array();
        foreach ($nestedArray as $key => $arrElement) {
            if  (is_array($arrElement)) {
                $subResults = self::getAllValuesAsOneDemisionalArray($arrElement);
                $result = array_merge($result, $subResults);
            } else {
               $result[] = $arrElement;
            }
        }
        
        return $result;
    }
    
    /**
     * Вернёт индекс (ключ) самого длинного вложенного подмассива массива
     * 
     * @param  array $nestedArray   массив, которые может содержать вложенные массивы
     * @return mixed               имя ключа (индекса)
     */
    public static function getKeyOfLogestSubarray($nestedArray)
    {
        $maxLength = 0;
        $arrayKeyName = false;
        foreach ($nestedArray as $key => $value) {
            if (is_array($value)) {
                $length = count($value);
                if ($length >= $maxLength) {
                    $maxLength = $length;
                    $arrayKeyName = $key;
                }
            }
        }
        
        return $arrayKeyName;
    }
}