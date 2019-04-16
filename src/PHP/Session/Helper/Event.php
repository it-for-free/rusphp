<?php

namespace ItForFree\rusphp\PHP\Session\Helper;

use ItForFree\rusphp\PHP\Session\Session;

/**
 * Работает с хранилищем сессии PHP
 * Автоматически стартуает сесию для своих методов (если она не была запущена ранее)
 *
 * @todo Можно  было бы писать их в 
 */
class Event 
{
    
    /**
     * @var string ключ подмассива в $_SESSION по которому будут хранится данные хэлпера
     */
    protected static $sectionName = '_session_events_storage';
   
    /**
     * Произошло ли это событие первый раз.
     * 
     * Проверит наличие флага  с имененм $eventName (выставленного в true),
     * в подмассиве жранилища сессий, затем
     * в любом случае поднимет флаг (считается что событие наступило).
     * 
     * @param type $eventName
     * @retrun boolean true если  флага не было или он был опущен, false во всех сотальных случаях
     */
    function isFirstTime($eventName)
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
