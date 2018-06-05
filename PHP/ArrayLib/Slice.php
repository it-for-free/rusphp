<?php

namespace ItForFree\rusphp\PHP\ArrayLib;


/**
 *  Для реализации получения различных "срезов" массива, например,
 *  конкретной строки с проверкой существовавания.
 */
class Slice
{
    /**
     * Получит срез по "строке", с поддержкой скалярных значений 
     * в соответствии с документацией self::forScalar()
     * 
     * @param mixed $value           значение, из которого нужно получить срез
     * @param int $rowNumber         номер строки
     * @param int $startScalarIndex  Нужно для проверки скаляра
     * @return mixed|false  подмассив, скалярное значение или false в случае неудачи
     */
    public static function getRow($value, $rowNumber, $startScalarIndex = 0)
    {
        $result = false;
        
        if (is_array($value)) {
            if (isset($value[$rowNumber])) {
               $result = $value[$rowNumber];
            }
        } else {
            $result = self::forScalar($value, $rowNumber, $startScalarIndex);
        }
        return $result;
    }
    
    /**
     * Извлекает "срез" для скаляра (не массива), 
     * цель -- работать со скалярным значение (напр типа int) как с массивом
     * 
     * если переданный $index совпадает  со $startIndex
     * то вернёт значение, иначе false
     * 
     * @param mixed $value     значение для которого надо взять срез
     * @param int $index       позиция среза (условно "номер строки")
     * @param int $startIndex  если это значение совпадает с int, значит $value и есть требуемое значение
     * @return mixed|false
     */
    public static function forScalar($value, $index, $startIndex = 0)
    {   
        $result = false; 
        if ($index === $startIndex) {
           $result = $value;
        }
        return $result;
        
    }
}