<?php

namespace ItForFree\rusphp\Html\Table\traits;


use ItForFree\rusphp\PHP\ArrayLib\Structure  as ArrayStructure;
use ItForFree\rusphp\PHP\ArrayLib\ArrCommon  as ArrCommon;
use ItForFree\rusphp\Log\SimpleEchoLog as Log;

/**
 * Для рассчета colspan и, прежде всего, rowspan ячеек сущности
 * 
 */
trait RowColSpanCounter
{
   

    /**
     * Рассчитает colspan и rowspan для ячеек даннной суности
     * (что, В частности, позволит, склеить пустые ячейки по вертикале при выводе).
     * 
     * Основная работа происходит с колонками данного массива
     * 
     * @param array $entityRows массив (двумерный) с непрерывными числовыми индексами, начинающимися с нуля, все строки которого одинаковой длины
     * @return array            тот же массив, но с правками в содержимом элементов строк
     */
    protected static function countSpans($entityRows)
    {
        $columnCount = count($entityRows[0]);
        for ($i = 0; $i++; $i <= ($columnCount - 1)) { // движемся по колонкам
            $column = array_column($entityRows, $i);
            
        }
        
        
        
        return array();
        
    }
 
}

