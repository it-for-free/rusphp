<?php
/**
 * Всегда загружаемые функции, в т.ч. кратки удобные псевдонимы для вызова
 */



if (!function_exists('rpath')) {
  
    /**
     * Добавит данный путь к пути от корня сайта 
     * @see зависит от $_SERVER
     * @param type $additioanlPath
     * 
     * @return string
     */
    function rpath($additionalPath) {
        return \ItForFree\rusphp\File\Path::addToDocumentRoot($additioanlPath);
    }
}
