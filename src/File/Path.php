<?php

namespace ItForFree\rusphp\File;

use ItForFree\rusphp\OS\OSCommon as OS;

/**
 * Для работы с файлами --
 * их путями и расширениями
 * 
 * @author vedro-compota 
 */
class Path {
    
    /**
     * Получить путь к файлу (или просто имя) в виде строки без расширения файла
     * 
     * @param string $pathStr -- сторка, в конце которой ожидается расширение
     * @return string
     */
    public static function getWithoutFileExtention($pathStr) 
    {
        $path_parts = \pathinfo($pathStr);
        $pathWithoutExt = $path_parts['dirname'] . DIRECTORY_SEPARATOR . $path_parts['filename'];
        
        return $pathWithoutExt;
    }
    
    /**
     * Изменить расширение файла в строке пути к нему 
     * например вместо 
     *    /path/to/this/file.txt
     * вернёт:
     *    /path/to/this/file.sql
     *
     * @param string $pathStr путь к файлу или его имя
     * @param string $newExt  новое расширение (а точнее хвостовая часть пути -- можно добавить что в конец имени файла + расширение)   
     * @return string
     */
    public static function getWithNewFileExtention($pathStr, $newExt)
    {
        $result = self::getWithoutFileExtention($pathStr) . $newExt;
        return $result;
    }
    
    /**
     * Получит имя файла из строки, содержащей полный путь
     */
    public static function getFileName($path)
    {
        return basename($path);
    }
    
    /**
     * Определит путь с учетом операционной системы
     * 
     * @param array $pathVariantsArray  например ['windows' => 'D:/tmp/', 'unix' => '/home/']
     * @return type
     */
    public static function forOS($pathVariantsArray)
    {
        return $pathVariantsArray[OS::getType()];
    }
    
    /**
     * Получит расширение файла из пути к нему
     * 
     * @param string $filePath
     * @return string
     */
    public static function getExtention($filePath)
    {
        $path_parts = \pathinfo($filePath);
        return $path_parts['extension'];
    }
    
    /**
     * Добавит данный путь к пути от корня сайта 
     * @see зависит от $_SERVER['DOCUMENT_ROOT'] 
     * @param string $additioanlPath  путь, который нужно прибавить к пути к корню сайта 
     * 
     * @return string
     */
    public static function addToDocumentRoot($additioanlPath)
    {
        $path = $_SERVER['DOCUMENT_ROOT'] 
                . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
        return $path;
    }
}
