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
 * (html таблица, вложенные документы, объединение ячеек)
 * 
 */
abstract class ArrayRebuilder
{
    
    use \ItForFree\rusphp\Html\Table\traits\ArrayRebuilderResultInHtmlTest;
    use \ItForFree\rusphp\Html\Table\traits\RowColSpanCounter;
    
    /**
     * Исходные данные, которые планируется выводить в виде html-таблицы
     * 
     * @var array 
     */
    protected $sourceArray = array();
    
    
    /**
     * Сюда из needleElementsAndSubarrays будут извелены "имена колонок"
     * таблицы, которую мы строим (с учётом вложенности).
     * Ведь по сути этот класс разворачивает массив со вложенностями в думерный (таблицу)
     * 
     * @var array
     */
    protected $columnNames = array();
    
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
    protected $needleElementsAndSubarrays = array();

    /**
     * Результат для рассчета colspan и rowspan каждой ячейки-
     * 
     * @var array
     */
    protected $resultWithoutRowColSpans = array();
    
    /**
     * Массив-результат (построенный с новой структурой для табличного вывода)
     * 
     * @var array
     */
    protected $result = array();
    
    /**
     * Ключевая функция, построит "массив строк html таблицы" 
     * (каждый элемент -- массив, описывающий ячейки)
     * 
     */
    abstract protected function rebuildEntity($entityData);
    
    public function __construct($sourceArray, $needleElementsAndSubarrays) {
        $this->sourceArray = $sourceArray;
        $this->needleElementsAndSubarrays = $needleElementsAndSubarrays;
        $this->getColumnNames($needleElementsAndSubarrays);
    }
    
    
    /**
     * Главный вызываемый метод 
     * 
     * @return array  массив в виде удобным для вывод в html
     */
    public function rebulid()
    {
        $result = array();
        foreach ($this->sourceArray as $key => $entity)
        {
            $result = array_merge($result, $this->rebuildEntity($entity));
        }
        
        $this->result = $result;
        
        return $result;
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
    protected static function getCell($content, $rowspan = 1, $emptyCell = false, $colspan = 1)
    {
        return array(
            'content' => $content,
            'emptyCell' => $emptyCell, 
            'rowspan'  => $rowspan,                 
            'colspan' => $colspan
        );
    }  

    /**
     * Извлечёт данные в нужном виде
     * 
     * @param array $needleElementsAndSubarrays
     */
    public function getColumnNames($needleElementsAndSubarrays)
    {
        $this->columnNames = ArrayStructure::getAllValuesAsOneDemisionalArray($needleElementsAndSubarrays);
    }
    
    /**
     * Сгенерирует и вернёт массив пустых ячеек
     * 
     * @param type $count
     * @return type
     */
    protected static function getEmptyCells($count)
    {
        $result = array();
        for ($i = 0; $i <= ($count-1); $i++) {
            $result[] = self::getEmptyCell();
        }
        
        return $result;
    }
    
    
    /**
     * Вернёт данные для пустой ячейки
     * 
     * @return array  массив её полей
     */
    protected static function getEmptyCell()
    {
        return self::getCell('', 1, true, 1);
    }     

}

