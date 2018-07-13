<?php

namespace ItForFree\rusphp\File;

use ItForFree\rusphp\OS\OSCommon as OS;

/**
 * Для работы с временными файлами
 * -- теми что создаются tmpfile()
 * 
 * @author vedro-compota 
 */
class TempFile {
    
    /**
     * Скопирует файл в временную папку (и временный файл, лежащей в ней)
     * и вернёт дескриптор созданного временного файла.
     *  
     * -- смотрите чтобы ссылки на него не пропали до тех пор, пока 
     * он вам нужен.
     * 
     * @param string $sourceFilePath путь к файлу-источнику
     * @return resource  декскриптор временнйо копии
     */
    public static function copy($sourceFilePath)
    {
        $temp = tmpfile();
        fwrite($temp, file_get_contents($sourceFilePath));
        return $temp;
    }
    
    /**
     * Вернёт путь к временному файлу
     * 
     * @param  resource $tmpFileDescriptor  дескриптор временного файла (возвращается с т.ч. при вызове tmpfile())
     * @return string
     */
    public static function getPath($tmpFileDescriptor)
    {
        $meta_data = stream_get_meta_data($tmpFileDescriptor);
        $filename = $meta_data["uri"];
        
        return $filename;
    }
}
