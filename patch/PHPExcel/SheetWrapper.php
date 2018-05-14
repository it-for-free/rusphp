<?php

namespace ItForFree\rusphp\patch\PHPExcel;

/**
 * Обёртка над PHPExcel_Worksheet 
 *
 * @author qwer
 */
class SheetWrapper {
    
    /**
     *
     * @var PHPExcel_Worksheet 
     */
    protected $sheet = null;
    
    
    /**
     * 
     * @param PHPExcel_Worksheet $sheet экземпляр класса для работы  с документом
     */
    public function __construct(&$sheet) {
        $this->sheet = $sheet;
  
    }
    
}
