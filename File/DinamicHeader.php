<?php

namespace ItForFree\rusphp\File;

use ItForFree\rusphp\File\Path as FilePath;
use ItForFree\rusphp\File\MIME as FileMIME;

/**
 *  Установит заголовки для файлов, формирующихся "на лету" (без сохранения)
 */
class Header
{

    /**
     * Установит заголовки для файлов, формирующихся "на лету" (без сохранения)
     * 
     * @param string $fileName  имя файла (подразумевается, что содержит расширение)
     */
    public static function dowloadByExtention($fileName)
    {
        $fileExtension = FilePath::getExtention($fileName);
        $mimeType  = FileMIME::byExtention($fileExtension);
        
        header('Content-Disposition: inline; filename="' . $fileName . '"');
        header('Content-type: ' . $mimeType);
        
    }
}