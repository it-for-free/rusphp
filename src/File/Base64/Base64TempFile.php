<?php

namespace ItForFree\rusphp\File\Base64;

/**
 * Для работы с временными файлами
 * -- теми что создаются tmpfile()
 * 
 * @author vedro-compota 
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
}
