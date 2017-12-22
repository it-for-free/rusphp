<?php

namespace ItForFree\rusphp\Html\Table;
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
     * -- массив с вложенными подмассивами, где перечислены имена ключей, которые следует извлекать из данных
     * 
     * @var array
     */
    private $needleElementsAndSubarrays = array();
    
    public function __construct($sourceArray, $needleElementsAndSubarrays) {
        $this->sourceArray = $sourceArray;
        $this->needleElementsAndSubarrays = $needleElementsAndSubarrays;
    }
    
    public function rebulid()
    {
        $result = array();
        foreach ($this->sourceArray as $key => $row)
        {
            $result[$key] = $this->rebulidRow($row);
        }
        
        return $result;
    }
    
    /**
     * Вернёт данные для конкретной строки таблицы
     * 
     * @param type $rowArray
     */
    public function rebuildRow($rowSourceArray)
    {
        
    }
    
    
    /**
     * Посчитает реальное число колонок с учетом вложенности
     * (поддерживает 1 уровень вложенности)
     * 
     * @param array $needleElementsAndSubarrays -- массив с вложенными подмассивами, где перечислены имена ключей, которые следует извлекать из данных
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

