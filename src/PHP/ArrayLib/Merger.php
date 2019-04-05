<?php

namespace ItForFree\rusphp\PHP\ArrayLib;


use ItForFree\rusphp\PHP\ArrayLib\helpers\UnsetArrayValue;
use ItForFree\rusphp\PHP\ArrayLib\helpers\ReplaceArrayValue;

/**
 * Всевозможные функции объединения массивов и их полей
 * 
 * массив, работа с массивами
 */
class Merger
{
    /**
     * Конкатенирует соотвествующие значения двух массивов, добавляя второе к первому
     * 
     * Подразумевается, что массивы имеют одинаковое число полей с обычными 
     * числовыми индексами (или одинаковыми строковыми). 
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

    /**
     * Рекурсирсивное слияние массивов
     * Если во втором значении есть то же строковый ключ, что что и в первом, 
     * то первое будет затёрто вторым (в этом отличие от array_merge_recursive())
     * 
     * Также есть возможность вообще удалить элемент из первого 
     * если во втором на соответствующую
     * позицию добавить экземпляр класса UnsetArrayValue
     * или заменить эту позицию в первом без слияния, если на ту же позицию
     *  во втром добавить  экземпляр: ReplaceArrayValue
     * 
     * -------
     * Идея и релализации взята из Yii2 (yii\helpers\ArrayHelper::merge()):
     * 
     * Merges two or more arrays into one recursively.
     * If each array has an element with the same string key value, the latter
     * will overwrite the former (different from array_merge_recursive).
     * Recursive merging will be conducted if both arrays have an element of array
     * type and are having the same key.
     * For integer-keyed elements, the elements from the latter array will
     * be appended to the former array.
     * You can use [[UnsetArrayValue]] object to unset value from previous array or
     * [[ReplaceArrayValue]] to force replace former value instead of recursive merging.
     * @param array $a array to be merged to
     * @param array $b array to be merged from. You can specify additional
     * arrays via third argument, fourth argument etc.
     * @return array the merged array (the original arrays are not changed.)
     */
    public static function mergeRecursivelyWithReplace($a, $b)
    {
        $args = func_get_args();
        $res = array_shift($args);
        while (!empty($args)) {
            $next = array_shift($args);
            foreach ($next as $k => $v) {
                if ($v instanceof UnsetArrayValue) {
                    unset($res[$k]);
                } elseif ($v instanceof ReplaceArrayValue) {
                    $res[$k] = $v->value;
                } elseif (is_int($k)) {
                    if (array_key_exists($k, $res)) {
                        $res[] = $v;
                    } else {
                        $res[$k] = $v;
                    }
                } elseif (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                    $res[$k] = self::mergeRecursivelyWithReplace($res[$k], $v);
                } else {
                    $res[$k] = $v;
                }
            }
        }

        return $res;
    }
    
    /**
     * php Объединение массивов с сохранением ключей 
     * (если все ключи уникальны то второй массив будет добавлен после первого - порядок элементо сохранится)
     * 
     * @param array $arr1
     * @param array $arr2
     * @return array
     */
    public static function mergeSavingKeys($arr1, $arr2)
    {
        $result  = array();
        foreach ($arr1 as $key => $value) {
            $result[$key] = $value;
        }
        
        foreach ($arr2 as $key => $value) {
            $result[$key] = $value;
        } 
        
        return $result;
    }
}