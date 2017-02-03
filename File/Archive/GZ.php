<?php

namespace rusphp\File\Archive;

use rusphp\OS\OSCommon as OS;
use rusphp\File\Path as Path;

/**
 * Для работы с архивами .gz
 */
class GZ extends ArchiveCommon
{
    
    /**
     * Распаковывае/отrрывает архив расширения .gz
     * решение на базе @link http://stackoverflow.com/a/3293251
     * 
     * @param string $inputPath   путь к файлу-архиву
     * @param string $OutputPath  необязательное полное имя (путь) файла, в который пишется результат распаковки
     * @param int $bufferSize     размер буфера, его увеличением может увеличить скорость выполнения кода
     */
    public static function unpack($inputPath, $OutputPath = '', $bufferSize = 4096)
    {
        self::correctPaths($inputPath, $OutputPath);
        

        // Открываем файлы в двоичном режиме
        $file = gzopen($inputPath, 'rb');
        $out_file = fopen($OutputPath, 'wb');

        // Повтор пока не достигнем конца файла
        while(!gzeof($file)) {
            // сразу записываем очередной распакованный фрагмент
            fwrite($out_file, gzread($file, $bufferSize)); 
        }

        // закрываем файлы
        fclose($out_file);
        gzclose($file);  
    }
}
