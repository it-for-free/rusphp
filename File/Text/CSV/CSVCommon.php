<?php

namespace rusphp\Text\Csv;

use rusphp\Common\ArrayLib\Merger as ArrayMerger;
use rusphp\Common\ArrayLib\ArrCommon as cArray;
use rusphp\Log\SimpleEchoLog as log;

/**
 * Общий фукнционал для работы с CSV
 */
class CSVCommon 
{
    /**
     *  Максимальна ожидаемая длина строки файла (например  .csv)
     * 
     * @var type 
     */
    public $maxLineLength = 2000;
    
    
    /**
     * Преобразует содержимое CSV файла в многомерный массив
     * 
     * @param string $inputFilePath   путь к файлу
     * @param string $csvDelimiter    разделитель
     * @return type
     * @throws Exception
     */
    public static function toArray($inputFilePath, $csvDelimiter = ',')
    {
        
        $result = [];
        $countLines = 0;
        
        if (($inputHandle = \fopen($inputFilePath, 'r')) !== FALSE) { // если удалось открыть оба файла
            
            while (($data = \fgetcsv($inputHandle, 2000, $csvDelimiter)) !== FALSE) { // пока не достигли конца файла
                 $countLines++;
                if (!empty($data)) {
                     $result[] = $data;   
                }
            }
            fclose($inputHandle);
        } else {
            throw new Exception("[!] ERROR: Can't open file $filePath");   
        }
        
        return $result;
    }
    
    
    /**
     * Запишет массив (в т.ч. ассоциативный) в CSV файл (построчно)
     * 
     * @param array $arr                массив (часто записывать)
     * @param string $outputFilePath    путь к файлу (куда записывать)
     * @param char $delimiter           разделитель (запятая по умолчанию)
     * @throws \Exception
     */
    public static function arrayToCSV($arr, $outputFilePath, $delimiter = ',')
    {
        if (($outputHandle = \fopen($outputFilePath, 'w')) !== FALSE) { // если удалось открыть оба файла
            
            foreach ($arr as $key => $row)
            {
                fputcsv($outputHandle, $row, $delimiter);
            }

            fclose($outputHandle);
        } else {
            throw new \Exception("{Error!} Can't open File! : $outputFilePath");
        }
    } 

}