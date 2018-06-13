<?php

namespace rusphp\Text\Csv;

use rusphp\Common\ArrayLib\Merger as ArrayMerger;
use rusphp\Common\ArrayLib\ArrCommon as cArray;
use rusphp\Log\SimpleEchoLog as log;

/**
 * Экселевскую талицу можно созданить как csv, но ячейки такой таблицы могут 
 * иметь много строк (не быть объеденены)
 * 
 * Цель данного функции состоит в том, чтобы объединить строки ячеек подобной таблицы,
 * уже после её перегона (иными средствами - за пределами этого скрипта в CSV)
 */
class LineInCellsMerger
{
     /**
     *  Максимальна ожидаемая длина строки файла (например  .csv)
     * 
     * @var type 
     */
    public $maxLineLength = 2000;
    
    
    /**
     * Отконвертирует файл.
     * 
     * Непустое значение одного из столбцов должно быть признаком 
     * начала новой результирующей строки
     * 
     *  
     * @param type $inputFilePath
     * @param type $outputFilePath
     * @param type $neededFields
     * @param type $inputDelimiter
     * @param type $outputDelimiter
     */
    public function mergeCells(
            $inputFilePath, 
            $outputFilePath, 
            $flagColumnNumber,
            $innerNewCellDelimiter = ' ',
            $inputFileDelimiter = ',', 
            $outputFileDelimiter = '|')
    {
        $count = 0;
        $countLines = 0;
        
        $currentData = [];
        if ((($inputHandle = \fopen($inputFilePath, 'r')) !== FALSE)
                && ($outputHandle = \fopen($outputFilePath, 'w')) !== FALSE) { // если удалось открыть оба файла
            
            while (($data = \fgetcsv($inputHandle, 2000, ",")) !== FALSE) { // пока не достигли конца файла
                 $countLines++;
                if (!empty($data)) {
                    
                    // ПРИЗНАК того. что началась новая строка таблицы
                    if (!empty(trim($data[$flagColumnNumber], ' \t'))) { // если столбец-признак необходимости открытия новой строки в результирующем файле не пуст, то:
                       
                        if (!empty($currentData)) { // пишем имеющиеся данные, если они не пусты
                           $count++;
                           //log::pre($currentData, " $count  // Line nub $countLines ---------------------------------------------------------- ");
                           //log::pre($data, 'cur:');
                           
                           
                            \fputcsv($outputHandle, cArray::trimAllFields($currentData), $outputFileDelimiter); 
                        }
                        $currentData = []; // сбрасываем 
                    }

                    $currentData = ArrayMerger::mergeСorrespondingArrayFiedsValues($currentData, $data, ' '); // объединяем данные

                   // log::pre($currentData, 'current^');
                   // log::pre($data, 'cur:');
                    
                }
            }
            
            if (!empty($currentData)) {
                \fputcsv($outputHandle, cArray::trimAllFields($currentData), $outputFileDelimiter);
            }
            
            
            fclose($inputHandle);
            fclose($outputHandle);
        }
        
        
        log::me('--------------<br>ALL: ' . $count);
    }
    

}