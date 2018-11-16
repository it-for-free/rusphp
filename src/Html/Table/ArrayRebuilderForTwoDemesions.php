<?php

namespace ItForFree\rusphp\Html\Table;


use ItForFree\rusphp\PHP\ArrayLib\Structure  as ArrayStructure;
use ItForFree\rusphp\PHP\ArrayLib\ArrCommon  as ArrCommon;
use ItForFree\rusphp\PHP\ArrayLib\Slice as Slice;
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
    protected function rebuildEntity($entitySourceArray, $countRowColSpans = true)
    {
        $entityRows = array();
        if (ArrCommon::hasSubarray($entitySourceArray)) {
            $entityRows = $this->getEntityResultForTwoDemisionalArray($entitySourceArray);
        } else {
            $entityRows = $this->getEntityResultForOneDemisinalArray($entitySourceArray); // только одна строка
        }
        
        if ($countRowColSpans) {
           // Log::me('считаем спаны');
           $entityRows = self::countSpans($entityRows);
        }

        
        return $entityRows;
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
        $needleSubArraysKeys = ArrayStructure::getSubArraysKeys($this->needleElementsAndSubarrays);
        $maxLenghtName = ArrayStructure::getKeyOfLogestSubarray($twoDemArr, $needleSubArraysKeys);
        
        $result = array();
        $inEntityTableRowNumber = 0;

        if (!empty($twoDemArr[$maxLenghtName])) { // не пуст ли этот массив
            foreach ($twoDemArr[$maxLenghtName] as $subArrayKey => $value) {

                $sliceValues = $this->getValueFromSlice($twoDemArr, $inEntityTableRowNumber);
                $result = array_merge($result, $sliceValues);

                $inEntityTableRowNumber++;
            }
        } else { // если самым "глубоким" в этой строке оказался пустой массив
            $result = $this->getValueFromSlice($twoDemArr, 0); // просто берём 1 срез, так как по сути строка только 1 (массив лиш формально двумерен)
        }      
        
        return $result;
    }

    /**
     *  Возвращает массив -- то что можно называть одной (видимой) строкой  html таблицы
     * 
     * "Режет" массив слоями (снимает очередной слой), вне зависимости, 
     *  является ли конкретное поле подмассивом (если не подмассив -- то создаётся пустая ячейка)
     * 
     * Подразумеваются, элементы вложенных массивов имеют одинаковую струкутру (число полей)
     * А также, что элементы вложенных массивов имеют именованные ключи (чтобы можно было понять к какой колонке относится)
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
        
       // Log::me('',"-------начинаем выбирать знчения из среза---------------");
        $result[0] = array();
        $currentCoumnNumber = 0;
        //print_r($result[0]);
        foreach ($needleElementsKeyNames as  $needleKeyName => $needle) {
           
            $needle = $this->needleElementsAndSubarrays[$needleKeyName];
            
            self::checkDataExistsOrAddEmptyFake($twoDemArr, $needle, $needleKeyName);
            
            //Log::me('Извлекаем для среза значение эламента:'); Log::echoFirstOrSecondIfFirstIsArray($needle, $needleKeyName);
            if (is_array($needle)) {
                
               //Log::me('--------это массив-----------');
                 
                $slice = Slice::getRow($twoDemArr[$needleKeyName], $inEntityTableRowNumber);
                if ($slice !== false) {
                    foreach ($needle as $needleSubFiledName) { // срезаем слой в подмассива
                        //Log::me("------------------поле масива--$needleSubFiledName-----------");
                       // Log::me('-------------------------Номер колонки: ' . $currentCoumnNumber 
                              //  . ' значение: ' . $twoDemArr[$needleKeyName][$inEntityTableRowNumber][$needleSubFiledName]);
                        $result[0][$currentCoumnNumber] = 
                                self::getCell($twoDemArr[$needleKeyName][$inEntityTableRowNumber][$needleSubFiledName]);
                       // print_r($result[0]);
                        $currentCoumnNumber++;
                    }

                } else { // если такого среза нет
                   // Log::me('--------пустой массив-----------');
                    $emptyCount = count($needle);
                    $emptyColumns = self::getEmptyCells($emptyCount);// заполняем нулями
                    $result[0] =  array_merge($result[0], $emptyColumns); // корректно ли объединение?
                    $currentCoumnNumber += $emptyCount;
                   // print_r($result[0]);
                }

            } else {
                //Log::me('Номер колонки перед присваиванием: ' . $currentCoumnNumber);
                $result[0][$currentCoumnNumber] = self::getCellFromScalar(
                        $twoDemArr[$needle], $inEntityTableRowNumber, 
                        $currentCoumnNumber);
                $currentCoumnNumber++;
                //print_r($result[0]);
            }
        }

        return $result;    
    }
    
 
    
   
    /**
     * Вернёт значение ячейки, возможно, как пустое
     * 
     * @param mixed $sourceScalar       значение сущности (не подмассив) для которого надо получить ячейку с четом номер среза
     * @param int $sliceIndex           номер среза (по сути номер строки html таблицы данной сущности)
     * @return type
     */
    protected function getCellFromScalar($sourceScalar, $sliceIndex)
    {
        
       // Log::me('это не массив, значение: ' . $sourceScalar);
        
        $result = self::getEmptyCell();
        $slice = Slice::getRow($sourceScalar, $sliceIndex);
        if ($slice !== false) {
           $result = self::getCell($sourceScalar);
        } else {
           $result = self::getEmptyCell(); 
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
        $result[0] = array();
        $columnNames = $this->columnNames; 
        foreach ($columnNames as $key => $colName)
        {
            $result[0][$key] =  self::getCell($oneDemArr[$colName]);  // извлекаем только нужное
        }
        
        return $result ;
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
     * Проверит, что данное значенире вообще есть в исходном массиве.
     * Если есть, то никаких действий не предпринимается, если нет,
     * то для скаляра создаётся значение с пустой строкой, а для массива 
     * задаётся только одна строка "как в требуемом списке", где все значения выставляются как пусте строки.
     * 
     * @todo Возможно, эту функцию надо вынести выше -- в родительский класс (вопрос в том, как она будет испоьлзоваться для организации кода для
     * произвольной вложенности)
     * 
     * 
     * @param array $entityData данные сущности в виде массива - отсюда мы извлекаем данные для очередной сторки сущности
     * @param string $needle     имя требуемого поля (из массима, описывающего таблицу)
     * @param string $needleKeyName имя ключа требуемого поля (из массима, описывающего таблицу), используется ТОЛЬКО для извлчения подмассивов
     */
    protected static function checkDataExistsOrAddEmptyFake(&$entityData, $needle, $needleKeyName)
    {
        if (is_array($needle)) {
            if (empty($entityData[$needleKeyName])) { // если массив  пуст
                $entityData[$needleKeyName][0] = 
                        ArrCommon::getArrayWithEmptyStringValues($needle);
            }
        } else {
            if (!isset($entityData[$needle])) { // если скаляр вообще не определён
                $entityData[$needle] = '';
            } 
        }
    }
    
   
}

