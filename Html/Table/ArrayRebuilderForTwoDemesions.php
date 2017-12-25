<?php

namespace ItForFree\rusphp\Html\Table;


use ItForFree\rusphp\PHP\ArrayLib\Structure  as ArrayStructure;
use ItForFree\rusphp\PHP\ArrayLib\ArrCommon  as ArrCommon;
use ItForFree\rusphp\Log\SimpleEchoLog as Log;


/**
 * 
 * Перестроит массив, элементы котрого содержат
 *  вложенные масивы атк, чтобы его было удобно выводить в html таблицу
 * определить colspan и rowspan для каждого элемента
 * 
 * Поддерживает одноуровневую вложенность
 */
class ArrayRebuilderForTwoDemesions extends ArrayRebuilder
{
    
    public function __construct($sourceArray, $needleElementsAndSubarrays) {
        
        parent::__construct($sourceArray, $needleElementsAndSubarrays);
        
    }
    
    public function rebulid()
    {
        $result = array();
        foreach ($this->sourceArray as $key => $row)
        {
            $result[$key] = $this->rebuildEntity($row);
        }
        
        $this->result = $result;
        
        return $result;
    }
    
    /**
     * Вернёт данные для конкретной сущности (с учетом вложенности разместив её в несокльих строках таблицы)
     * (в этой функции заключена основная сложность)
     * 
     * 
     * @param array $entitySourceArray
     * 
     * Каждый элемент $rowArray Будет состоять из массивов (каждый затем можно будет отразить 
     * в строку html  таблицы) Какждый элемент такого вложенног массива будет содержать
     * информацию о ячейке в виде струкуры от self::getInfoForCell()
     * 
     * @return array    -- массив, который можно назвать строками html таблицы, но для одной сущности.
     */
    public function rebuildEntity($entitySourceArray)
    {
        $result  = array();
        if (ArrCommon::hasSubarray($entitySourceArray)) {
            $result = $this->getEntityResultForTwoDemisionalArray($entitySourceArray);
        } else {
            $result[0] = $this->getEntityResultForOneDemisinalArray($entitySourceArray); // только одна строка
        }
        
        return $result;
    }
    
    
    /**
     * Получит результат для двумерного массива
     * Подразумевается, что элементы подмассивов имеют соответствующие числовые ключи,
     * начинаюищиеся с нуля
     * -- иначе нужно изменять способ обхода.
     * 
     * @param type $twoDemArr
     */
    private function getEntityResultForTwoDemisionalArray($twoDemArr)
    {
        $maxLenghtName = ArrayStructure::getKeyOfLogestSubarray($twoDemArr);
        
        $result = array();
        $inEntityTableRowNumber = 0;
        
        foreach ($twoDemArr[$maxLenghtName] as $subArrayKey => $value) {
            
            $sliceValues = $this->getValueFromSlice($twoDemArr, $inEntityTableRowNumber); 
            $result = array_merge($result, $sliceValues);
    
            $inEntityTableRowNumber++;
        }
        
        return $result;
    }

    /**
     *  Возвращает массив -- то что можно называть одной строкой html таблицы
     * 
     * "Режет" массив слоями (снимает очередной слой), вне зависимости, 
     *  является ли конкретное поле подмассивом (если не подмассив -- то создаётся пустая ячейка)
     * 
     * Подразумеваются, элементы вложенных массивов имеют одинаковую струкутру (число полей)
     * А также, что элементы вложенных массивов имеют именованные ключи (чтобы можно было понять к какой коолонке относится)
     * 
     * @param array $twoDemArr         двумерный массив: в нём могут быть как единичные элементы так и подмассивы
     * @param int $inEntityTableRowNumber   номер среза для сущности (число срезов определяется глубиной вложенности)
     * 
     *  @return array   массив "строк таблицы" (в каждой подмассив ячеек), для данной сущности
     */
    private function getValueFromSlice($twoDemArr, $inEntityTableRowNumber)
    {
        
        //print_r($twoDemArr);
        $needleElementsKeyNames = $this->needleElementsAndSubarrays;
       
        $result = array();
        $currentCoumnNumber = 0;
        foreach ($needleElementsKeyNames as  $needleKeyName => $needle) {
           
            $needle = $this->needleElementsAndSubarrays[$needleKeyName];
        
            if (is_array($needle)) {
                
                $slice = ArrCommon::getRowIfIsset($twoDemArr[$needleKeyName], $inEntityTableRowNumber);
                if ($slice) {
                    foreach ($needle as $needleSubFiledName) { // срезаем слой в подмассива
                        $result[0][$currentCoumnNumber] = 
                                $twoDemArr[$needleKeyName][$inEntityTableRowNumber][$needleSubFiledName];
                        $currentCoumnNumber++;
                    }

                } else { // если такого среза нет
                    $emptyCount = count($needle);
                    $emptyColumns = self::getEmptyCells($emptyCount);// заполняем нулями
                    $result[0] =  array_merge($result[0], $emptyColumns); // корректно ли объединение?
                    $currentCoumnNumber += $emptyCount;
                }

            } else {
                $result[0][$currentCoumnNumber] = $twoDemArr[$needle];
            }
        }

        return $result;    
    }
    
    /**
     * Сгенерирует и вернёт массив пустых ячеек
     * 
     * @param type $count
     * @return type
     */
    private static function getEmptyCells($count)
    {
        $result = array();
        for ($i = 0; $i <= ($count-1); $i++) {
            $result[] = self::getInfoForCellStructure('', 1, true, 1);
        }
        
        return $result;
    }
        
    /**
     * Извлекаем значение для строки из обычного массива --
     * тут объект будет представлен одной тсоркой таблицы, так ка нет вложенных данных
     * (если это не вызов с какого-то уровня рекурсии других функций класса)
     * 
     * @param type $oneDemArr  
     */
    private  function getEntityResultForOneDemisinalArray($oneDemArr)
    {
        $result = array();
        $columnNames = $this->columnNames; 
        foreach ($columnNames as $key => $colName)
        {
            $result[$key] =  self::getInfoForCellStructure($oneDemArr[$colName]);  // извлекаем только нужное
        }
        
        return $result;
    }
    
    
    /**
     * Посчитает реальное число колонок с учетом вложенности
     * (поддерживает 1 уровень вложенности)
     * 
     * @param array $needleElementsAndSubarrays -- массив с вложенными подмассивами, 
     *                      где перечислены имена ключей, которые следует извлекать из данных
     * @return int
     */
    public static function getRowCount($needleElementsAndSubarrays)
    {
        $count = 0;
        foreach ($needleElementsAndSubarrays as $element)
        {
           if (is_array($element)) {
               foreach ($element as $elementValue) {
                  $count++; 
               }
           } else {
               $count++;
           } 
        }
        
        return $count;
    }
    
   
}

