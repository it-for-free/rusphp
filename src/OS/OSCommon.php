<?php

namespace ItForFree\rusphp\OS;

use ItForFree\rusphp\PHP\ArrayLib\Merger as ArrayMerger;
use ItForFree\rusphp\PHP\ArrayLib\ArrCommon as cArray;
use ItForFree\rusphp\Log\SimpleEchoLog as log;

/**
 * Общие функции связанные, например с определением типа системы
 */
class OSCommon
{
    /**
     * Вернёт строку типа ОС -- windows или unix 
     * (linux считае unix-подобной)
     * 
     * @return string  --  windows или unix 
     */
    public static function getType()
    {
        $result = 'unix';
            if (self::isWindows()) {
               $result = 'windows';
            }
        
        return $result;
    }
    
    /**
     * Определит принадлежит ли ОС к семье Windows
     */
    public static function isWindows() {
        $result = false;
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $result = true;
        }

        return $result;
    }
}