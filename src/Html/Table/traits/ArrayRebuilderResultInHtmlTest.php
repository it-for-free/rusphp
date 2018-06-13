<?php

namespace ItForFree\rusphp\Html\Table\traits;


use ItForFree\rusphp\PHP\ArrayLib\Structure  as ArrayStructure;
use ItForFree\rusphp\PHP\ArrayLib\ArrCommon  as ArrCommon;
use ItForFree\rusphp\Log\SimpleEchoLog as Log;

/**
 * Методы вывода результатов в HMTL -- используются в основном для отладки
 * 
 */
trait ArrayRebuilderResultInHtmlTest
{
   
    /**
     * Построит для уже известных результатов html таблицу 
     * -- можно использовать в отладке
     * 
     * @return string
     */
    protected function getResultAsHTMLTable($useRowColSpans = false)
    {
        $result = $this->result;
        $html = '<table border=1>';
        $columnNames = $this->columnNames;
         $html .= '<thead><tr>';  
        foreach ($columnNames as $columnName) {
            $html .= "<th> $columnName </th>";
        }
        $html .= '</tr></thead>';
        
        foreach ($result as $row) {
            $html .= '<tr>';
                foreach ($row as $cell) {
                    //Log::pre($cell, 'ячейка');
                    $html .= $this->td($cell, $useRowColSpans);
                }
            $html .= '</tr>';
           
        }
        $html .= '</table>';
        
        return $html;
    }
    
    /**
     * Вернёт тэг td html таблицы с нужными атрибутами и содержимым для данной ячейки
     * 
     * @param array  $cell
     * @param boolean $useRowColSpans
     * @return string
     */
    protected function td($cell, $useRowColSpans = false) {
        $content = $this->getCellContent($cell);
        
        $result  = '';
        if ($useRowColSpans && !$cell['emptyCell'] ) {
            $result = '<td rowspan=' . $cell['rowspan'] 
                    . ' colspan=' . $cell['colspan'] . '>' 
                    . $content . '</td>';
        } else if (!$useRowColSpans){
            $result = '<td>' . $this->getCellContent($cell) . '</td>'; 
             
        }
        
        return $result;
    }
    
    /**
     * Можно переопределить вывод контента для пустых ячеек
     * 
     * 
     * @param array $cell
     * @return array
     */
    protected function getCellContent($cell)
    {
        $result = ''; // [[empty]]
        if (!$cell['emptyCell']) {
           $result = $cell['content'];
        }
        
        return $result;
    }
    
    
    /**
     * Выведет в виде html -- для тестирования
     */
    public function printResultHtmlTest()
    {
        echo $this->getResultAsHTMLTable();
        echo('<br>-----Withrowspans--------<br>');
        echo $this->getResultAsHTMLTable(true);
    }  
    
    
    
    /**
     * Распечатка для отладки входящих данных и некоторых результатов 
     * (в т.ч. промежуточных)
     */
    public function printSource()
    {
        Log::pre($this->sourceArray, 'Входящий массив');
        Log::pre($this->result, 'Результат');
    }
    

}

