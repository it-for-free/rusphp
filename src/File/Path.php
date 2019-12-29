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
     * Добавит слева  к переданному пути $additionalPath путь
     * до корня сервера 
     * 
     * @see зависит от $_SERVER['DOCUMENT_ROOT'] 
     * @param string $additionalPath  путь, который нужно прибавить к пути к корню сайта 
     * 
     * @return string
     */
    public static function addToDocumentRoot($additionalPath)
    {
        $path = static::removeEndSlash($_SERVER['DOCUMENT_ROOT'])
            . static::addStartSlash($additionalPath);
        return $path;
    }
    
    /**
     * Добавит разделитель директорий в конец пути (прямой или обратый слеш), 
     * если путь итак им не оканчивается.
     * 
     * @param string $path  путь, которому надо добавить в конец слеш, если его там нет
     * @param type $separator (опционально) разделитель, по умолчанию используется значение из DIRECTORY_SEPARATOR 
     * @return string
     */
    public static function addEndSlash($path, $separator = '')
    {
        if ($separator === '') {
            $separator =  DIRECTORY_SEPARATOR;
        }
        return rtrim($path, $separator) .  $separator;
    } 
    
    /**
     * Корректно объединит фрагменты пути (без проверки на наличие слеша)
     * 
     * @param string[] $paths  Массив путей, которые нужно объединить
     * @param string $separator разделитель (по умолчанию DIRECTORY_SEPARATOR)
     * @return string
     */
    public static function concat($paths, $separator = '')
    {
        $result = '';
        if ($separator === '') {
            $separator =  DIRECTORY_SEPARATOR;
        }
        
        $count = 0;
        
        foreach ($paths as $path) {
           
           $count++;
           $added = '';
            if ($count === 1) {
               $added = static::addEndSlash($path);
           } else if ($count < count($paths)) {
               $added = static::removeStartSlash(static::addEndSlash($path));
           } else {
               $added = static::removeStartSlash($path);
           } 
           
           $result .= $added;
        }
        
        return $result;
    } 
    
    /**
     * Добавит разделитель директорий в начало пути (прямой или обратый слеш), 
     * если путь итак c него не начинается.
     * 
     * @param string $path  путь, которому надо добавить в начало слеш, если его там нет
     * @param string $sepator разделитель (укажите, если нужно что-то нестадартное), 
     *      по умолчанию будет использоваться системная константа DIRECTORY_SEPARATOR
     * 
     * @return string
     */
    public static function addStartSlash($path, $sepator = '')
    {
        if (empty($sepator)) {
            $sepator = DIRECTORY_SEPARATOR;
        }
        return (DIRECTORY_SEPARATOR 
            . ltrim($path, DIRECTORY_SEPARATOR));
    }
    
    /**
     * Удалит разделитель директорий (прямой или обратый слеш), 
     * если путь им  оканчивается.
     * 
     * @param string $path  путь, с конца которого надо удалить разделитель диреткторий (если он там есть)
     * @param string $sepator разделитель (укажите, если нужно что-то нестадартное), 
     *      по умолчанию будет использоваться системная константа DIRECTORY_SEPARATOR
     * @return string
     */
    public static function removeEndSlash($path, $sepator = '')
    {
        if (empty($sepator)) {
            $sepator = DIRECTORY_SEPARATOR;
        }
        return rtrim($path, $sepator);
    } 
    
    /**
     * Удалит разделитель директорий (прямой или обратый слеш), 
     * если путь с него начинается.
     * 
     * @param string $path  путь, с конца которого надо удалить разделитель диреткторий (если он там есть)
     * @param string $sepator разделитель (укажите, если нужно что-то нестадартное), 
     *      по умолчанию будет использоваться системная константа DIRECTORY_SEPARATOR
     * @return string
     */
    public static function removeStartSlash($path, $sepator = '')
    {
        if (empty($sepator)) {
            $sepator = DIRECTORY_SEPARATOR;
        }
        
        return ltrim($path, $sepator);
    } 

    /**
     * Добавит разделитель директорий в начало и конец пути, если их там нет (прямой или обратый слеш), 
     * если путь итак им не оканчивается.
     * 
     * @param string $path  путь, которому надо добавить начальный и конечные слеши, если их там нет
     * @return string
     */
    public static function addSlashes($path)
    {
        return  DIRECTORY_SEPARATOR . 
                trim($path, DIRECTORY_SEPARATOR)
                .  DIRECTORY_SEPARATOR;
    }
    
    /**
     * Удалит из пути путь до корня сайта, и вернёт то, что получилось
     * 
     * @param string $path            путь (подразумевается, что абсолютный)
     * @param boolean $withStartSlash добавлять ли слеш в начале специально (если его там не было)
     * @return type
     */
    public static function getWithoutDocumentRoot($path, $withStartSlash = false)
    {
        $relativePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $path);
        if ($withStartSlash) {
            $relativePath = static::addStartSlash($relativePath);
        }
        return $relativePath;
    }
}
