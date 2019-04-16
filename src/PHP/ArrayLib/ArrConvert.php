<?php

namespace ItForFree\rusphp\PHP\ArrayLib;

/**
 * Конвертирование элементов массива - перевод из одного состояния в другое
 */
class ArrConvert
{
    /**
     * Все пустые строки будут заменены на null 
     * (с сохранением ключей массива)
     * 
     * @param array $arr  исходный массив
     * @param array $keys ключи, для которых происходить замену (по умолчанию - для всех ключей)
     * @return array
     */
    public static function emptyLinesToNull($arr)
    {
        $result = array();
        
        foreach ($arr as $key => $value) {
            if ($value === '') {
               $result[$key] = null; 
            } else {
               $result[$key] = $value; 
            }
        }
        
        return $result; 
    }

    /**
     * Значения с пустыми строки по указанным ключам будут замены на null
     * (с сохранением ключей массива)
     * 
     * @param array $arr  исходный массив
     * @param array $keys ключи, для которых происходить замену (по умолчанию - для всех ключей)
     * @return array
     */
    public static function emptyLinesToNullForKeys($arr, $keys = array())
    {
        $keysEmpty = empty($keys);
        $result = array();
        
        if ($keysEmpty) {
            return static::emptyLinesToNull($arr);
        } else {
            foreach ($arr as $key => $value) {
                if ($value === '' && in_array($key, $keys)) {
                   $result[$key] = null; 
                } else {
                   $result[$key] = $value; 
                }
            }
        }

        return $result;        
    }
}