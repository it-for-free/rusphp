<?php

namespace ItForFree\rusphp\File\Base64;

use UI\Draw\Path;
use ItForFree\rusphp\File\MIME;

/**
 * Для работы с временными файлами
 * -- теми что создаются tmpfile()
 * 
 * @author vedro-compota  base on idea: https://stackoverflow.com/a/11511605
 * 
 * @see data: URL https://ru.wikipedia.org/wiki/Data:_URL
 */
class Base64TempFile {
    
    /**
     * @var string 
     */
    private $sourceData = null;
    
    /**
     * @var resource
     */
    private $tempResource = null; 
       
    public function __construct($base64string)
    {
        $this->sourceData = $base64string;
        
        $temp = tmpfile();
        fwrite($temp, file_get_contents($this->sourceData));
        
        $this->tempResource = $temp;
    }
    
    /**
     * Вернёт путь к временному файлу
     * 
     * @return string
     */
    public function getPath()
    {
        $meta_data = stream_get_meta_data($this->tempResource);
        $filename = $meta_data["uri"];
        
        
        return $filename;
    }
    
    public function copyTo($newPath)
    {  
        return copy($this->getPath(), $newPath);
    }
    
    /**
     * Попробует извлечь расширение,
     *  если исходная сторка в формате data: URL (см. https://ru.wikipedia.org/wiki/Data:_URL)
     * 
     * а-ля /^data:(\w+)\/(\w+);base64,/
     * 
     * @return string
     */
    public function getExtention()
    {
        $result  = '';
        if (preg_match('/^data:(\w+)\/(\w+);base64,/', $this->sourceData, $type)) {
            $result = strtolower($type[2]);
        }
        
        return $result;
    }
    
    /**
     * Convert our file to base64 data url format
     * 
     * @param string $filePath
     * @return string
     */
    public static function convertToDataUrl($filePath)
    {
        return 'data:' . MIME::getForFile($filePath) . ';base64,'
            . base64_encode(file_get_contents($filePath));
    }
    
    
    /**
     * Return Mime from source "url data" prefix if exists
     * 
     * @return string
     */
    public function getMime()
    {
        return MIME::byExtention($this->getExtention());
    }
}
