<?php
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
