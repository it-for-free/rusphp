<?php
namespace ItForFree\rusphp\File\Directory;

use ItForFree\rusphp\File\Path;

/**
 * Для работы с директориями (папками)
 * (clear folder directory -- delete all files)
 */
class Directory {
   
    /**
     *  Удалит всё из директории (полностью очистит её)
     * ((Remove all files, folders and their subfolders with php))
     * 
     * @param string $path         путь к директории, которую надо очистить
     * @param boolean $removeSelf  удалять ли диреторию верхнего уровня (саму переданную)
     */
    public static function clear($path, $removeSelf = false)
    {   

        if (is_dir($path)) {
           $objects = scandir($path);
           foreach ($objects as $object) {
             if ($object != "." && $object != "..") {
               if (filetype($path."/".$object) == "dir") 
                  static::clear($path."/".$object, true); 
               else unlink   ($path."/".$object);
             }
           }
           reset($objects);
           
           if ($removeSelf) {
               rmdir($path);
           }   
         }
    }
    
    /**
     * Создаст директорию рекурсивно (если ону уже не существует)
     * 
     * @param string $path
     * @param number $permissions
     * @throws \Exception
     */
    public static function createRecIfNotExists($path, $permissions = 0777)
    {
        if (!is_dir($path)) {
            if (!mkdir($path, $permissions, true)) {
                    throw new \Exception("Can't create directory $path recursivly!");
            }
        }
    }
    
    
    /**
     * Вернет массив путей ко всем файлам в папке
     * 
     * @param string $dirPath
     * @return string[]
     */
    public static function getAllFilesPaths($dirPath)
    {
        $files = scandir($dirPath);
        
        $result = [];
        foreach ($files as $fileName) {
            if ($fileName !== '.' && $fileName !== '..') {
                $result[] = Path::concat([$dirPath, $fileName]);
            }
        }
        
        return $result;
    }
}
