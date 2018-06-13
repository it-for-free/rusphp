<?php

namespace ItForFree\rusphp\patch\PHPExcel;
use ItForFree\rusphp\Log\SimpleEchoLog;

/**
 * Основной класс дающий более удобный интерфейс для добавления ячеек в таблицу, с учетом colspan и rowspan 
 * (объединения ячеек), я вляется обёрткой над PHPExcel_Worksheet
 * Будет работать корректно если вы до начала его использования не добавляли значения в 
 * экземпляр PHPExcel_Worksheet напрямую.
 * 
 * Реализует примерно тот же механизм что HTML-интерпретатор при парсинге кода,
 * но с учетом того, что любое олбъединение ячеек может быть исполнено вне зависимости от того заданы ли они далее в таблице, 
 * так как эксель уже даёт "бесконечное" поле единчных ячеек.
 * 
 * 
 * Добавление колонок выглядит так (вычислять позицию на листе не нужно):
 * 
```php 
        use aItForFree\rusphp\patch\PHPExcel\Table as ExcelTable;
 
        $Table = new ExcelTable($sheet);
  
        $Table->add(1, 'Данные', 1, 7);
        $Table->add(1, 'Степень ', 1, 4);
  
        $Table->add(2, 'значение', 2, 1); // во вторую строку таблицы
        $Table->add(2, 'Уровень ', 1, 3);
  ```
 *
 * @author qwer
 */
class Table extends SheetWrapper {
    
    private $rows = array();

 
    /**
     * ОБщий метод для добавления
     * 
     * @param  int $rowNumber
     * @param int $text
     * @param int $rowSpan
     * @param int $colSpan
     */
    public function add($rowNumber, $text = '', $rowSpan = 1, $colSpan = 1)
    {
        $rowNumber = $rowNumber + 1;
        $this->buildEnoughRows($rowNumber); // если требуется расширяем таблицу    
        $freeColumn = $this->getFirstFreeColumnNumberInRow($rowNumber);
        
//        if  ($text == '№ п.п') {
//         echo $rowNumber;  echo $freeColumn; 
//         SimpleEchoLog::pre($this->rows);
//         die();
//        }
        
        $this->sheet->setCellValueByColumnAndRow($freeColumn, $rowNumber, $text);
        $this->sheet->mergeCellsByColumnAndRow($freeColumn, $rowNumber,
                $freeColumn + $colSpan - 1,
                $rowNumber + $rowSpan - 1);
                
//        $sheet->setCellValueByColumnAndRow(0, 4, 'Округа и субъекты РФ');
//        $sheet->mergeCellsByColumnAndRow(0, 4, 0, 5);
        
        $this->markAsClosed($rowNumber, $freeColumn, $rowSpan, $colSpan); // отмечаем занятые данной ячейкой позиции в сетке элементарных ячеек
    }
    
    /**
     * В общем случае вычеркнет используемый прямоугольник "элементарных ячеек"
     * 
     * @param type $startRowNumber
     * @param type $startColNumber
     * @param type $rowSpan
     * @param type $colSpan
     */
    protected function markAsClosed($startRowNumber, $startColNumber,  $rowSpan = 1, $colSpan = 1)
    {
        $this->markAsClosedInRow($startRowNumber, $startColNumber, $colSpan); // та, в которой начинается результирующая ячейка
        
        $this->buildEnoughRows($startRowNumber + $rowSpan - 1); // достраиваем если требуется
        
        for ($i = $startRowNumber + 1; $i <= ($startRowNumber + $rowSpan - 1); $i++) {
            $this->markAsClosedInRow($i , $startColNumber, $colSpan);
        } 
    }
    
    /**
     * Вычеркнет занятые элементарные ячейки в конкретной  строке
     * 
     * @param type $rowNumber
     * @param type $startColNumber
     * @param type $colSpan
     */
    protected function markAsClosedInRow($rowNumber, $startColNumber,  $colSpan = 1)
    {
        $Row = $this->rows[$rowNumber];
        
        // отметим занятое в текущей строке
        for ($i = $startColNumber; $i <= ($startColNumber + $colSpan -1); $i++) {
            $Row->closed[] = $i;
        }
        $Row->closed = array_unique($Row->closed); // удаляем неуникальное
    }
    
    /**
     * 
     * @param int $rowNumber до какой строки расширить ожидаемую высоту таблицы
     */
    protected function buildEnoughRows($rowNumber)
    {
        for ($i = 0; $i <= $rowNumber; $i++) {
            if (empty($this->rows[$i])) {
                $this->rows[$i] = new Row($i);
            }
        }
    }
    
    /**
     * Ищем ближайшее незанятое местое в строке -- и не важно насколько оно свободно 
     * правее.
     * 
     * @param type $rowNumber
     */
    protected function getFirstFreeColumnNumberInRow($rowNumber)
    {
        $result = -1;
        $Row = $this->rows[$rowNumber];
        $closed = $Row->closed;
        //print_r($closed);
        if (empty($closed)) { // если ничего нет, то начинаем с первой (нулевой) позиции
            $result = 0;
        } else {
            $max = max($closed);
            for  ($i = 0; $i <= $max; $i++) {
               // echo $i . '/';
                if (!in_array($i, $closed)) { // проверяем заполненую область на пустоты
                    $result = $i;
                    break;
                }
            } 
           // echo '</br>';
            if ($result < 0) { // еcли пустот нет
                $result = $max + 1;  // то располагаемся справа после занятой области
            }
        }

        
        return $result;
    }
    
    
//    /**
//     * @todo Доделать
//     * 
//     * @param type $startRow
//     * @param type $startColumn
//     * @param type $corner
//     */
//    public function rotateText($startRow, $startColumn, $corner = 90)
//    {
//        $ершы->sheet->getStyleByColumnAndRow($startColumn, $startRow + 1)->getAlignment()->setTextRotation($corner);
//    }
    
    
}
