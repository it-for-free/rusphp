<?php

namespace ItForFree\rusphp\PHP\ArrayLib;


/**
 * Для "обращения" порядка элементов массиов и 
 * иной работы с такими массивами.
 * (обратный порядок)
 */
class Reverce
{
    /**
     * Вычислит порядковый номер элемента в массиве (числовой индекс), до того 
     * как порядок следования элементов был измененён на обратный
     *
     * @param int $afterReverceIndex              текущий порядковый номер элемента
     * @param int $inArrayElementsCount           число элементов в массиве  
     * @param int $startArrayIndexBeforeReverse   начальный индекс (по умолчанию = 0) -- подразумевается, что он одинаковый в обоих массивах 
     * @return int
     */
    public static function getIndexBefore($afterReverceIndex, $inArrayElementsCount, $startArrayIndexBeforeReverse = 0)
    {     
       return (($inArrayElementsCount - 1)- $afterReverceIndex + $startArrayIndexBeforeReverse);
    }
    
    /**
     * Вернёт элемент массива по номеру, так как если бы порядок следования элементов был изменён на обратный
     * null в случае отсутсвия элемента.
     * 
     * @param array $array      сам массив
     * @param int $indexNumber  гипотетический номер элемента в отражённом массиве
     * @retrun mixed  Элемент массива или null, если элемент отсутствует.
     */
    public static function getElement($array, $indexNumber) 
    {
        $result = null;
        
        if (isset($array[count($array) - $indexNumber])) {
            $result = $array[count($array) - $indexNumber];
        }
        
        return $result;
    }
}