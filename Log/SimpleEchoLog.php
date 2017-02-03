<?php

namespace rusphp\Log;

/**
 * Простое логгирование "а браузер"
 */
class SimpleEchoLog
{
    public static $log = true;
    
    /**
     * пусть относительно корня сайта
     */
    public static $outputTextFilePath = '/log.txt'; 
    
    public static $logInFileEnabled = false;
    
    


    /**
     * Простой вывод в браузер
     * 
     * @param mixed $var
     */
    public static function me($var, $comment = '') {
        
        if (self::$log) {
            echo "<br>$comment" . $var . '<br>';
        }
        
        if (self::$logInFileEnabled) {
           self::logInFile($var);  
        } 
    }
    
    /**
     * Добавит информацию в файл
     * 
     * @param string $str
     */
    public static function logInFile($str) {

        $log = "\n" . $str . "\n";
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . self::$outputTextFilePath , $log, FILE_APPEND);
    }
    
    /**
     * Выведет HTML шапку
     * 
     * @param string $title
     */
    public static function htmlHead($title = 'Отладка кода', $encoding = 'utf-8')
    {
        $html = '<html>
        <html>  
        <head>
            <meta http-equiv="content-type" content="text/html; charset=' . $encoding . '" />
            <title>  ' . $title . ' </title> 
        </head>
        <body>';
        
        echo $html;
    }
    
    
    /**
     * Вывод сообщения, или возврат строки с окруженирем тэгами
     * 
     * @param mixed  $var        логгируемый объект или переменная
     * @param string $comment    комментарий
     * @param bool $returnOnly   возвращать (true) или просто выводить на экран
     * @return string
     */
    public static function pre($var, $comment = '--', $returnOnly = false) {
        
        if (self::$log) {    
            if ($returnOnly) {
                return "$comment:<pre>" . print_r($var, true) . '</pre>';
            } else {
                echo "<br>$comment:<pre>" . print_r($var, true) . '</pre>';
            }
        }
    }
    
    public static function hr()
    {
        echo '<hr>';
    }
    
}