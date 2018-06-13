<?php

namespace rusphp\File\Archive;

use rusphp\Log\SimpleEchoLog as log;
use rusphp\OS\OSCommon as OS;
use rusphp\File\Path as Path;
use rusphp\PHP\Str\StrCommon as Str;
use rusphp\File\Archive\ArchiveConfig as Config;

/**
 * Для работы с архивами
 */
class ArchiveCommon {
    
    /**
     * Распаковывает архив 
     */
    public static function unpack($inputFilePath,  $OutputPath = '', $bufferSize = 4096)
    { 
        self::callUnpackFromClass($inputFilePath,  $OutputPath, $bufferSize);
    }
    
    
    /**
     * Вызовет функцию unpack подходящего класса-обработчика (если таковой есть в библиотеке в данном пространстве имн - иначе получим ошибку)
     * 
     * @param type $inputFilePath
     * @param type $OutputPath
     * @param type $bufferSize
     */
    public static function callUnpackFromClass($inputFilePath,  $OutputPath = '', $bufferSize = 4096)
    {
        $className = Str::up(Path::getExtention($inputFilePath)); // имя класса -- расширение файла большими буквами
       
        
        call_user_func_array([__NAMESPACE__ .'\\' .  $className, 'unpack'],
                [$inputFilePath,  $OutputPath, $bufferSize]); // вызываем функции распаковки соответствующего класса
    }
    
    /**
     * Проверит и при необходимости исприт пути
     * например, задаст значения по умолчанию
     * 
     * @param string $inputFilePath
     * @param string $outputFilePath
     * @return true
     */
    public static function correctPaths(&$inputFilePath, &$outputFilePath)
    {
        if (!$outputFilePath) { // если не указан. то будем распаковывать в ту же папку, но из имени файла удалим расширение .gz
            $outputFilePath = Path::getWithoutFileExtention($inputFilePath);
        }
        
        return true;
    }
    
    /**
     * Проверит является ли файл по данному пути архивом
     * 
     * @param  string $fullFileName
     * @return boolean
     */
    public static function isArchive($fullFileName)
    {
        return in_array(Path::getExtention($fullFileName), Config::$extentions);
    }
    
    /**
     * Вернёт имя файла без расширения архива, возможно добавив новое расширение
     * 
     * @param string $fullFileName  путь к файлу
     * @param string $newExtention  новое расширение без точки
     * @return string
     */
    public static function unpuckedName($fullFileName, $newExtention = '')
    {
        if ($newExtention) {
            $newExtention = '.' . $newExtention;
        }
        
        $name = $fullFileName;
        if (self::isArchive($fullFileName)) {
            $name = Path::getWithoutFileExtention($fullFileName) . $newExtention;
        }
        
        return $name;
    }
}
