<?php

namespace ItForFree\rusphp\patch\PHPExcel;

/**
 *  Строка таблицы
 */
class Cell extends SheetWrapper {
    
    private $columns = array();
    
    /**
     *
     * @var int 
     */
    private $number;
    
    /**
     * 
     * @param int $sheet        Номер строки
     * @param type $rowNumber
     */
    public function __construct(&$sheet, $rowNumber) {
        parent::__construct($sheet);
        $this->number = $rowNumber;
    }
    
}
