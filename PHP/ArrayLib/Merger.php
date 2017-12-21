<?php

namespace ItForFree\rusphp\PHP\ArrayLib;


/**
 * Всевозможные функции объединения массивов и их полей
 * 
 * массив, работа с массивами
 */
class Merger
{
    /**
     * Подразумевается, что массивы имеют одинаковое число полей с обычными 
     * числовыми индексами 
     * 
     * @param array $arr1
     * @param array $arr2
     * @param array $delimiter
     */
    public static function mergeСorrespondingArrayFiedsValues($arr1, $arr2, $delimiter = '')
    {
        $result = [];
         // объединяем соответствующие элементы
        if (!empty($arr1)) {
            foreach ($arr1 as $key => $val) { // елси первый не пуст
                $add = '';
                
                if (!empty($arr2[$key])) { // если второе значение не пусто
                   $add = $delimiter . $arr2[$key];
                }
                
                $result[$key] = $val . $add;
            }
        } else { // если 2-ой не пуст
            foreach ($arr2 as $key => $val) { 
                $result[$key] = $val;
            }
        }
        
        return $result;
    }
}