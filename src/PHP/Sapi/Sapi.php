<?php

/**
 * Для работы с SAPI
 * 
 * @author vedro-compota
 */
class Sapi {
    
    /**
     * Проверит запущен ли скрипт из командной строки (консоли, терминала)
     * [[check if run from command line]]
     * 
     * @return boolean
     */
    public static function isConsole()
    {
        return (php_sapi_name() == "cli");
    }
}
