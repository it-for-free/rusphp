<?php

namespace ItForFree\rusphp\File;

/**
 * Для получения mime -- изначально просто обёртка
 */
class MIME
{
    
    public static function byExtention($fileExtension)
    {
        $mimes = new \Mimey\MimeTypes;
        
        return $mimes->getMimeType($fileExtension); 
    }
    
    
}