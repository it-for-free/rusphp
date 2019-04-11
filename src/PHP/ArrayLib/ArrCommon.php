<?php

namespace ItForFree\rusphp\PHP\ArrayLib;

use ItForFree\rusphp\PHP\Str\StrCommon as Str;
use ItForFree\rusphp\Log\SimpleEchoLog as log;

/**
 * Фунции для работы с массивами, котроым не нашлось места в специализированых классах с конктреным названием 
 * (или такие, для которых название сложно придумать)
 */
class ArrCommon
{
    
    /**
     * Обрежет все значения массива 
     * (удалит по умолчанию пробелы -- или ниые символы по маске)
     * 
     * @param  array $arr
     * @param  string $character_mask
     * @return array
     */
    public static function trimAllFields($arr, $character_mask = '')
    {
        foreach ($arr as $key => $val) {
            $arr[$key] = \trim($val, $character_mask);
        }
        
        return $arr;
    }
    
    /**
     * Проверить, что все переданные значение массива не пусты (на пустоту)
     * 
     * @param array $values проверяемые значения
     * @return bool
     */
    public static function allElementsNotEmpty($values)
    {
        $result = true;
        foreach ($values as $val) {
            if (empty($val)) {
                $result = false;
                break;
            }  
        }
        
        return $result;
    }
    

    /**
     * Получит все элементы массива, которые содержат  строку или несколько строк (подстроки)
     * 
     * @param string $arr     -- список строк, в которых искать
     * @param array $startStr -- список подстрок (которые искать)
     * @return array
     */
    public static function getElementsWith($arr, $startStrs, $trim = false)
    {
        $result = [];
        
        foreach ($arr as $val)
        {
            foreach ($startStrs as $startStr) {
                //log::pre($arr);
                if (Str::isInStr($val, $startStr)) {
                    
                    if ($trim) {
                        $str = trim($val);
                    } else {
                        $str = trim($val);
                    }
                    
                    $result[] = $str;
                }
            }
        }
            
        return $result;
    }

    /**
     * Удалит из массива (ассоциативного прежде всего) все 
     * элементы с указанными ключами
     * 
     * @param array $arr                 массив, среди ключей которого искать (базовый)
     * @param array $arrOfKeysToRemove   массив ключей, элементы по которым нужно удалить (вместе с ключем)
     * @return array
     */
    public static function removeAllElementsWithKeys($arr, $arrOfKeysToRemove) 
    {

        foreach ($arrOfKeysToRemove as $rKey) {
                unset($arr[$rKey]);
        }
        
        return $arr;
    }
    
    /**
     * Удалит $count первых элементов в массиве
     * 
     * @param array $arr  исходный массив
     * @param int $count  число элементов  (с начала), которые надо удалить
     * @return array 
     */
    public static function removeFirstElements($arr, $count)
    {
        $remCount = 0;
        foreach ($arr as $key => $value) {
            if ($remCount < $count) {
                unset($arr[$key]);
                $remCount++;
            } else {
                break;
            }
        }
        return $arr;
    }
    
    /**
     * Сортировка ассоциативного массива по ключам -- например, по алфавиту
     * 
     * @param type $arr
     * @param type $sort_flags
     */
    public static function sortByKeys(&$arr, $sort_flags = SORT_REGULAR)
    {
        ksort($arr, $sort_flags);
    }
  
   
    /**
     * Отсортирует массив (многомерный - массив массивов) по указанному полю
     * 
     * @param type $arr
     * @param type $keyName
     */
    public static function sortByField(&$arr, $keyName) {
        
        self::$sortByFieldName = $keyName;
        usort($arr, "self::compareTwoFileds"); /** @see self::compareTwoFileds() */
    }
    
    /**
     *
     * @var string имя ключа поля, по которому производить сравнение подмасиивово (строк) массива
     */
    private static  $sortByFieldName = ''; 
    
    /**
     * Сравнит две строки массива -- на основе сравнения их элементов по ключу  self::$sortByFieldName 
     * 
     * @param array $a  первая строка массива 
     * @param array $b  вторая строка массива
     * @return int
     * @throws Exception
     */
    private static function compareTwoFileds($a, $b)
    {
        $result = 0;
        
        if (isset($a[self::$sortByFieldName])
                && isset($b[self::$sortByFieldName])) {
            $result = Str::compareAsInAlphbet($a[self::$sortByFieldName], $b[self::$sortByFieldName]);  
        } else {
            throw new \Exception(' {ERROR} Theres NO filed with key  ' . self::$sortByFieldName . ' !');
        }
        
         return $result;
    }
    
    
    /**
     * Подсчитает число элементов в многомерном массиве (двумерном и выше)
     * НЕ используйте слишком часто для больших массивов @see http://fkn.ktu10.com/?q=node/8352
     * 
     * @param array $arr 
     * @return int
     */
    public static function countRecursive($arr)
    {
        return \count($arr, COUNT_RECURSIVE);
    }
    
    /**
     * Вернёт массив с заменой ассоциативных ключей (индексов) стандартными числовыми
     * Позволит обращаться к элементам ассоциативного массива по порядковому номеру
     * 
     * @param type $arr
     * @return type
     */
    public static function numberIndex($arr)
    {
        return array_values($arr);
    }
    
    
    /**
     * Проверит если ли в массиве вложенный подмассив
     * 
     * @param array $parentArray
     */
    public static function hasSubarray($parentArray)
    {
        $result = false;
        foreach ($parentArray as $value) {
            if (is_array($value)) {
                $result = true;
                break;
            }
        }
        
        return $result;
    }
    
    /**
     * Вернёт строку (срез) двумерного массива, еслио на есть
     * 
     * @param array $sourceArray
     * @param  int $rowNumber
     * @return boolean|array
     */
    public static function getRowIfIsset($sourceArray, $rowNumber)
    {
        $result = false;
        
        if (isset($sourceArray[$rowNumber])) {
           $result = $sourceArray[$rowNumber];
        }
        return $result;
    }
    
    /**
     * Вернёт строку (срез) двумерного массива, если она есть
     * 
     * @param  mixed $sourceMayBeArray
     * @param  int $number
     * @return boolean|array
     */
    public static function getRowIfIssetOrItselfForZeroIndex($source, $number)
    {
        $result = false;
        
        if (isset($sourceArray[$rowNumber])) {
           $result = $sourceArray[$rowNumber];
        } else if ($number == 0) {
            $result = $source;
        }
        return $result;
    }
    
    /**
     * Вернёт массив с данными ключами, у которого все значения буудт пустыми строками
     * 
     * пустые строки
     * 
     * @param  array $arrayKeys
     * @return array
     */
    public static function getArrayWithEmptyStringValues($arrayKeys)
    {
        $result  = array();
        
        foreach ($arrayKeys as $key) {
             $result[$key] = '';     
        }
        
        return $result;
    } 
    
    /**
     * Копирует (клонирует) массив -- в т.ч. объектов
     * 
     * ((copy clone array of objects))
     * 
     * @param mixed[] $arrayOfObjects
     * @param bool    $saveKeys        если false, то ключи будут заменены стандартными (численными)
     * @return mixed[]
     */
    public static function copy($arrayOfObjects, $saveKeys = true)
    {
        $new = array();
        if ($saveKeys) {
            foreach ($arrayOfObjects as $k => $v) {
                $new[$k] = clone $v;
            }
        } else {
            foreach ($arrayOfObjects as $v) {
                $new[] = clone $v;
            }
        }
        
        return $new;
    } 
    
    /**
     * Проверит меньше ли значение всех элементов из массива
     * 
     * @param mixed $element  значение
     * @param mixed[] $arr    массив, с элементами которого сравниваем
     * @param boolean $equial считать ли равенство допустимым (если <= то будем считать, что меньше)
     * @return boolean
     */
    public static function isLessOfAll($element, $arr, $equial = true)
    {
        $less = true;
        $min = min($arr);
        
        if ($equial) {
            if ($element > $min) {
                $less = false;
            }   
        } else {
            if ($element >= $min) {
                $less = false;
            }
        }
        return $less;
    }
    
    /**
     * Отфильтрует массив, оставивив в нём только элементы
     * с ключами из  $keysAr
     * 
     * ((php array get fields with keys))
     * 
     * @param array $arr             исходный массив
     * @param array $keysArr  массив нужных ключей
     * @param bool $setEmptyIfKeyNotExists  устанавливать ли в результат пустые
     *  значения, если очередного ключа в массиве нет
     *  (по умолчанию просто пропускаем)
     * @param mixed $defaultEmptyValue  значение, на случае если 
     * поднят флаг $setEmptyIfKeyNotExist (для пустых значений)
     * @return array  отфильтрованный массив
     */
    public static function getElemetsWithKeys($arr, $keysArr, $setEmptyIfKeyNotExists = false, $defaultEmptyValue = '')
    {
        $results = [];
        
        if ($setEmptyIfKeyNotExists) {
            foreach ($keysArr as $key) {
                if (array_key_exists($key, $arr)) {
                   $results[$key] = $arr[$key];
                } else {
                   $results[$key] = $defaultEmptyValue; 
                }
            }
        } else {
            foreach ($keysArr as $key) {
                if (array_key_exists($key, $arr)) {
                   $results[$key] = $arr[$key];
                }
            }
        }
        return $results;
    }
    
    /**
     * Рекурсивно проверит массив на пустоту.
     * Фактически идёт проверка на содержание
     *  в массиве на любом уровне непустых значений (не объектов)
     * 
     * ((php array empty recursive))
     * 
     * @param array $array
     * @return boolean
     */
    public static function isEmptyRecursive($array) 
    {
        if (empty($array)) {
            return true;
        } else {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    static::isEmptyRecursive($value);
                } else { // если это не массив
                    if (!empty($value)) {
                        return false;
                    }
                }
            }
            return true;
        }
    }
    
    /**
     * Оставит только уникальные значения 
     * и перенумерует их непрерывно стандартным для php образом
     * 
     * Работает на основе стандартных array_values() и array_unique()
     * 
     * @param mixed[] $arr исходный массив
     * @return mixed[]
     */
    public static function uniqueAndRenumerate($arr)
    {
        return array_values(array_unique($arr));
    }
}