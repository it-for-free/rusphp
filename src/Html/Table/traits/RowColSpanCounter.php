<?php

namespace ItForFree\rusphp\Html\Table\traits;


use ItForFree\rusphp\PHP\ArrayLib\Structure  as ArrayStructure;
use ItForFree\rusphp\PHP\ArrayLib\ArrCommon  as ArrCommon;
use ItForFree\rusphp\Log\SimpleEchoLog as Log;
use ItForFree\rusphp\PHP\ArrayLib\Reverce as ArrayReverce;


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
        return self::countRowSpans($entityRows);  
    }
    
    /**
     * Рассчитает  rowspan для ячеек даннной сущности
     * (что, В частности, позволит, склеить пустые ячейки по вертикале при выводе).
     * 
     * Основная работа происходит с колонками данного массива
     * 
     * @param array $entityRows массив (двумерный) с непрерывными числовыми индексами, начинающимися с нуля, все строки которого одинаковой длины
     * @return array            тот же массив, но с правками в содержимом элементов строк
     */
    protected static function countRowSpans($entityRows)
    {
        $columnCount = count($entityRows[0]);
        $rowCount = count($entityRows);
       // Log::me("Чило колонок $columnCount");
        
        for ($i = 0;  $i <= ($columnCount - 1); $i++) { // движемся по колонкам

            $columnNumber = $i;
           // Log::me("-----Колонка $columnNumber");
            
            $column = array_column($entityRows, $i);
            $revercedOrderColumnElements = array_reverse($column);
            $rowspanCount = 1;
            foreach ($revercedOrderColumnElements as $key => $cell) {
                //Log::me("объедиение $rowspanCount");
                if ($cell['emptyCell']) {
                    $rowspanCount++;
                } else {
                    $rowNumber = ArrayReverce::getIndexBefore($key, $rowCount);
                    $entityRows[$rowNumber][$columnNumber]['rowspan'] = $rowspanCount;
                    $rowspanCount = 1;
                }
            }
        }
 
        //Log::pre($entityRows, 'после расчета спанов');
        return $entityRows;    
    }
    
    
 
}
