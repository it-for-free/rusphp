<?php

namespace ItForFree\rusphp\File;

use ItForFree\rusphp\File\Path;

/**
 * Для получения mime -- изначально просто обёртка
 */
class MIME
{
    /**
     * mime тип для данного расширения
     * 
     * @param string $fileExtension
     * @return string
     */
    public static function byExtention($fileExtension)
    {
        $mimes = new \Mimey\MimeTypes;
        
        return $mimes->getMimeType($fileExtension); 
    }
    
    /**
     * Все mime типы для данных расшерений (или расшерения)
     * 
     * @param string|array $fileExtensions  расширения (или одно),
     *  для которых нужно получить список mime-типов
     * @return array of string
     */
    public static function getAllbyExtentions($fileExtensions)
    {
        $mimes = new \Mimey\MimeTypes;
        
        $results = [];
        
        if (is_array($fileExtensions)) {
            foreach ($fileExtensions as $ext) {
                $extMimes = $mimes->getAllMimeTypes($ext);
                foreach ($extMimes  as $mimeType) {
                    $results[] = $mimeType;
                }
            }
        } else {
            $results = $mimes->getAllMimeTypes($fileExtensions);
        }

        return array_unique($results); 
    }
    
    /**
     * First suitable MIME for your file 
     * 
     * @param  string $filePath
     * @return string
     */
    public static function getForFile($filePath)
    {
        return static::byExtention(Path::getExtention($filePath));
    }
}