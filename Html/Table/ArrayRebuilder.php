<?php

namespace ItForFree\rusphp\Html\Table;


use ItForFree\rusphp\PHP\ArrayLib\Structure  as ArrayStructure;
use ItForFree\rusphp\PHP\ArrayLib\ArrCommon  as ArrCommon;

/**
 * 
 * Перестроит массив, элементы котрого содержат
 *  вложенные масивы атк, чтобы его было удобно выводить в html таблицу
 * определить colspan и rowspan для каждого элемента
 * 
 * Поддерживает одноуровневую вложенность
 */
class ArrayRebuilder
{
    /**
     * Исходные данные, которые планируется выводить в виде html-таблицы
     * 
     * @var array 
     */
    private $sourceArray = array();
    
    /**
     * Описывает какие именно поля надо извлечь для таблицы
     * -- массив с вложенными подмассивами, где перечислены имена ключей, которые следует извлекать из данных.
     * 
     * По факту описывает структуры одной сущности, которая будет выведена в виде "основной строки (в которй могут быть вложенности)",
     * такие "строки" в таблицы могут повторяться  сколько угодно раз,
     * но структура их описывается именно  этим массивом.
     * 
     * @var array
     */
    private $needleElementsAndSubarrays = array();
    
    /**
     * Сюда из needleElementsAndSubarrays будут извелены "имена колонок"
     * таблицы, которую мы строим (с учётом вложенности).
     * Ведь по сути этот класс разворачивает массив со вложенностями в думерный (таблицу)
     * 
     * @var array
     */
    private $columnNames = array();
    
    
    /**
     * Массив-результат (построенный с новой структурой дял табличного вывода)
     * 
     * @var array
     */
    private $result = array();
    
    public function __construct($sourceArray, $needleElementsAndSubarrays) {
        $this->sourceArray = $sourceArray;
        $this->needleElementsAndSubarrays = $needleElementsAndSubarrays;
        $this->getColumnNames($needleElementsAndSubarrays);
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
            $result = self::getEntityResultForTwoDemisionalArray($rowSourceArray);
        } else {
            $result[0] = getEntityResultForOneDemisinalArray($rowSourceArray); // только одна строка
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
    private static function getEntityResultForTwoDemisionalArray($twoDemArr)
    {
        $maxLenghtName = ArrayStructure::getKeyOfLogestSubarray($twoDemArr);
        
        $result = array();
        $inEntityTableRowNumber = 0;
        
        foreach ($twoDemArr[$maxLenghtName] as $subArrayKey => $value) {
            
            $sliceValues = self::getValueFromSlice($twoDemArr, $inEntityTableRowNumber); 
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
    private static function getValueFromSlice($twoDemArr, $inEntityTableRowNumber)
    {
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
    private static function getEntityResultForOneDemisinalArray($oneDemArr)
    {
        $result = array();
        $columnNames = $this->columnNames; 
        foreach ($columnNames as $key => $colName)
        {
            $result[$key] =  self::getInfoForCellStructure($oneDemArr[$colName]);  // извлекаем только нужное
        }
        
        return $result;
    }
    
    private static function getInfoForCellIndependentIfArrayOrNot()
    {
    }
    
    
    /**
     * Вернёт инфромацию о ячейка табилицы в фиксированном формате
     * 
     * @param string|int $content  то что будет отображено в ячейке таблицы
     * @param int $rowspan
     * @param false $emptyCell
     * @param int $colspan
     * @return array   информацию о ячейке в виде:
     * [
     *   'content' => '...', // соответствующий контент из $rowSourceArray
     *   'emptyCell' => false, // true если данных этой ячейки вообще нет в исходном массиве (при выводе в html такие вообще можно пропускать)
     *   'rowspan'  => 1,  // или реальное значение (из-за неоднородной длины вложенных массиво) 
     *                    //   -- одна из основных задач этого класса -- рассчитать это число.
     *   'colspan'  => 1, // просто для возможной совместимости 
     * ]
     * 
     */
    private static function getInfoForCellStructure($content, $rowspan = 1, $emptyCell = false, $colspan = 1)
    {
        return array(
            'content' => $content,
            'emptyCell' => $emptyCell, 
            'rowspan'  => $rowspan,                 
            'colspan' => $colspan
        );
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
    
    
    /**
     * Извлечёт данные в нужном виде
     * 
     * @param array $needleElementsAndSubarrays
     */
    public static function getColumnNames($needleElementsAndSubarrays)
    {
        $this->columnNames = ArrayStructure::getAllValuesAsOneDemisionalArray($needleElementsAndSubarraysy);
    }
    
   
}

