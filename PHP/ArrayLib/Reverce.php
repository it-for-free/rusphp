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
}