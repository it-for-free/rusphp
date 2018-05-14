<?php

namespace ItForFree\rusphp\patch\PHPExcel;

/**
 *  Строка таблицы
 */
class Row  {
    
//    private $cells = array();
//
//    /**
//     *
//     * @var int 
//     */
//    private $number;
//    
    /**
     * занятые позиции в строке
     * @var type 
     */
    public $closed = array();
    
    /**
     *
     * @param int $rowNumber  Номер строки
     */
    public function __construct($rowNumber) {
       
        $this->number = $rowNumber;
    }
 
}
