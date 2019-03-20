<?php

use ItForFree\rusphp\Log\DebugStyles;
/**
 * Всегда загружаемые функции, в т.ч. кратки удобные псевдонимы для вызова
 */

if (!function_exists('rpath')) {
  
    /**
     * Добавит данный путь к пути от корня сайта 
     * @see зависит от $_SERVER['DOCUMENT_ROOT']
     * @param string $additioanlPath  путь, который нужно прибавить к пути к корню сайта 
     * 
     * @return string
     */
    function rpath($additioanlPath) {
        return \ItForFree\rusphp\File\Path::addToDocumentRoot($additioanlPath);
    }
}

/*------------------Обёртки над print_r()--------------------------------*/
if (!function_exists('pdie')) {
  
    /**
     * Распечатка print_r() и die() c окружением html-тегами pre
     * 
     * @param mixed $var      значение для распечатки
     * @param string $comment необязательный комментарий (пояснение)
     */
    function pdie($var, $comment = '') {
        if (!empty($comment)) {
            echo DebugStyles::$commentOpen . "$comment:" . DebugStyles::$commentClose;
        }
        echo DebugStyles::$preOpen; print_r($var); echo DebugStyles::$preClose; die();
    }
}

if (!function_exists('ppre')) {
  
    /**
     * "print pre": Распечатка print_r()  c окружением html-тегами pre
     * 
     * @param mixed $var      значение для распечатки
     * @param string $comment необязательный комментарий (пояснение)
     * @param mixed $var
     */
    function ppre($var, $comment = '') {
        if (!empty($comment)) {
            echo DebugStyles::$commentOpen . "$comment:" . DebugStyles::$commentClose;
        }
        echo DebugStyles::$preOpen; print_r($var); echo DebugStyles::$preClose;
    }
}
/*-------------------------------------------------------------------*/

/*------------------Обёртки над var_dump()---------------------------*/
if (!function_exists('vdie')) {
  
    /**
     * Распечатка var_dump() и die() c окружением html-тегами pre
     * 
     * @param mixed $var      значение для распечатки
     * @param string $comment необязательный комментарий (пояснение)
     */
    function vdie($var, $comment = '') {
        $hasXdebug = extension_loaded('xdebug');
        if (!empty($comment)) {
            echo DebugStyles::$commentOpen . "$comment:" . DebugStyles::$commentClose;
        }
        echo  (!$hasXdebug ? DebugStyles::$preOpen : DebugStyles::$xdebugOpen); 
        var_dump($var); 
        echo  (!$hasXdebug ? DebugStyles::$preClose: DebugStyles::$xdebugClose);
        die();       
    }
}

if (!function_exists('vpre')) {
    /**
     * Распечатка var_dump() c окружением html-тегами pre
     * 
     * @param mixed $var
     * @param string $comment необязательный комментарий (пояснение)
     */
    function vpre($var, $comment = '') {
        if (!empty($comment)) {
            echo DebugStyles::$commentOpen . "$comment:" . DebugStyles::$commentClose;
        }
        $hasXdebug = extension_loaded('xdebug');
        echo  (!$hasXdebug ? DebugStyles::$preOpen : DebugStyles::$xdebugOpen); 
        var_dump($var); 
        echo  (!$hasXdebug ? DebugStyles::$preClose: DebugStyles::$xdebugClose);      
    }
}

/*-------------------------------------------------------------------------*/

if (!function_exists('ptrace')) {
  
    /**
     * "print trace": Трассировка к точке вызова
     * 
     * @param string $comment необязательный комментарий (пояснение)
     */
    function ptrace($comment = '') {
        if (!empty($comment)) {
            echo DebugStyles::$commentOpen . "$comment:" . DebugStyles::$commentClose;
        }
        echo DebugStyles::$preOpen; 
        debug_print_backtrace(); 
        echo DebugStyles::$preClose;
    }
}