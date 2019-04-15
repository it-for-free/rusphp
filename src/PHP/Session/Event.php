<?php

namespace ItForFree\rusphp\PHP\Session;

use ItForFree\rusphp\PHP\Session\Session;

/**
 * Работает с хранилищем сессии PHP
 * Автоматически стартуает сесию для своих методов (если она не была запущена ранее)
 *
 * @todo Можно  было бы писать их в 
 */
class Event 
{
    
    protected static $sectionName = '_session_events_storage';
   
    /**
     * Проверит наличие флага $eventName (выставленного в true),
     * в любом случае поднимет флаг
     * 
     * @param type $eventName
     * @retrun boolean true если  флага не было или он был опущен, false во всех сотальных случаях
     */
    function isFirst($eventName)
    {
        Session::start();
        $result  = true;
        if (!empty($_SESSION[self::$sectionName][$eventName])) {
            $result  = false;
        } else {
            $_SESSION[self::$sectionName][$eventName] = true;
        }
        return $result;
    }
}
