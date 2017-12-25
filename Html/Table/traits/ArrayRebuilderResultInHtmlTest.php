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
    protected function getResultAsHTMLTable()
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
                    $html .= '<td>' . $cell['content'] . '</td>';
                }
            $html .= '</tr>';
           
        }
        $html .= '</table>';
        
        return $html;
    }
    
    
    /**
     * Выведет в виде html -- для тестирования
     */
    public function printResultHtmlTest()
    {
        echo $this->getResultAsHTMLTable();
    }  
    
    
    
    public function printSource()
    {
        Log::pre($this->sourceArray, 'Входящий массив:');
        Log::pre($this->result, 'Массив-результат:');
    }
    

}

