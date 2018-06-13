<?php

namespace rusphp\File;

/**
 * Разные операции над файлами
 *
 * @author 
 */
class Content {
    
    /**
     * Объединит несколько файлов в один (подразумевается работа с текстовыми файлами)
     * 
     * @param  array $pathsArray          массив путей к файлам, содержимое которых надо объединить
     * @param  string $pathToResultFile   путь к итоговому файлу
     */
    public static function mergeTextFiles($pathsArray, $pathToResultFile) 
    {
        if  (($outputHandle = \fopen($pathToResultFile, 'w')) !== FALSE) { // если удалось открыть итоговый на запись
            $str = '';
            foreach ($pathsArray as $key => $filePath) { // обходим массив путей файла
                
                if (($inputFileHandle = \fopen($filePath, 'r')) !== FALSE) {
                     
                    while (($str = \fgets($inputFileHandle)) !== FALSE) { // читаем очередную строку 
                        \fwrite($outputHandle, $str . "\n"); // пишем в итоговый файл
                    }
                    
                } else {
                    throw new \Exception("Can't open INPUT file: [$filePath]!");
                }
            }
        } else {
            throw new \Exception("Can't open OUTPUT file: [$pathToResultFile]!");
        }
       
    }
}
