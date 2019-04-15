<?php

namespace ItForFree\rusphp\PHP\Session;

/**
 * Description of FirstTime
 *
 * @author qwe
 */
class Session 
{
    
    /**
     * Стартует сессию, если та ранее не была запущена
     * (выполнит соответствующую проверку)
     * 
     * @return boolean true если сессия уже была запущена, или удачно запущена в этот раз, fals в противном случае
     */
    public static function start($options = array())
    {
        $result  = true;
        if (!static::isStarted()) {
            $result = session_start($options);
        }
        return $result;
    }
    
    /**
     * Проверит была ли уже стартована сессия
     * Источник кода: @see https://www.php.net/manual/ru/function.session-status.php#113468
     * 
     * @return boolean
     */
    public static function isStarted()
    {
        if (php_sapi_name() !== 'cli') {
            if ( version_compare(phpversion(), '5.4.0', '>=') ) {
                return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }
    
    /**
     * Сохранит данные сессии и завершит её
     * - фактически обертка над session_write_close()
     * 
     * @return boolean  true в лслучае удачного завершения 
     */
    public static function close()
    {
        return session_write_close();
    }
}
