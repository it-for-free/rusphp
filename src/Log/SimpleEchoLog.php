<?php

namespace ItForFree\rusphp\Log;

/**
 * Простое логгирование "а браузер" или в консоль 
 * (отладка, логгирование, поиск ошибок)
 */
class SimpleEchoLog extends SimpleLog
{

    /**
     * пусть относительно корня сайта
     */
    public static $outputTextFilePath = '/log.txt'; 
    
    public static $logInFileEnabled = false;
    
    
    /**
     * Вернёт комбинцию для переноса строки в зависимосмти от значения 
     * self::$inBrowserForHtml
     * 
     * @return string
     */
    protected static function newLineSymbol()
    {
        $result = "\n";
        if (static::$inBrowserForHtml) {
            $result = '<br>';
        }
        
        return $result;
    }
    
    /**
     * Вернёт сторку (строковый литерал) состоящую из символов переноса строки
     * (с учетом формата вывода)
     * 
     * @param int $needleNewLinesCount  необходиое число переновос строки
     * @return string
     */
    protected static function getNewLinesSymbolsGroup($needleNewLinesCount)
    {
        $result = "";
        
        for ($i = 0; $i < $needleNewLinesCount; $i++) {
            $result .= static::newLineSymbol();
        }
        
        return $result;
    }
    
    /**
     * Вернёт значение тэга для "оригинального"
     * вывода текста, в зависимости от формата вывода данного 
     * класса логирования
     * 
     * @return string
     */
    protected static function preStartSymbol()
    {
        $result = '';
        if (static::$inBrowserForHtml) {
            $result = '<pre>';
        }
        
        return $result;
    }
    
    
        /**
     * Вернёт значение тэга для "оригинального"
     * вывода текста, в зависимости от формата вывода данного 
     * класса логирования
     * 
     * @return string
     */
    protected static function preStartEnd()
    {
        $result = '';
        if (static::$inBrowserForHtml) {
            $result = '</pre>';
        }
        
        return $result;
    }


    /**
     * Простой вывод зачения 
     * (обёртка над echo())
     * 
     * @param mixed $var         то что рапечатываем 
     * @param string $comment    необязательный комментарий
     * @param int $newLinesBeforeCount Число переносов строк "до" 
     * @param int $newLinesAfterCount  Число переносов строк "после"
     */
    public static function me($var, $comment = '', $newLinesBeforeCount = 0, $newLinesAfterCount = 1) 
    {
        if (!self::$log) { return; }
        
        $comment = $comment ? ($comment .= ':') : '';
        
        if (self::$log) {
            echo  self::getNewLinesSymbolsGroup($newLinesBeforeCount) 
                    . $comment 
                    . $var 
                    . self::getNewLinesSymbolsGroup($newLinesAfterCount);
        }
        
        if (self::$logInFileEnabled) {
           self::logInFile($var);  
        } 
    }
    
    /**
     * Обёртка над
     *  ::me($var, $comment) + die() 
     * 
     * @param mixed $var         то что рапечатываем 
     * @param string $comment    необязательный комментарий
     */
    public static function med($var, $comment = '')
    {
        if (!self::$log) { return; }
        self::me($var, $comment);
        die();
    }
    
    /**
     * @deprecated since version 1.0.4 лучше используйте ItForFree\rusphp\Log\SimpleFileLog
     * Добавит информацию в файл
     * 
     * @param string $str
     */
    public static function logInFile($str) 
    {
        if (!self::$log) { return; }
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
        if (!self::$log) { return; }
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
     * Вывод значения через print_r, или возврат строки с окружением тэгами <pre>
     * 
     * @param mixed  $var        логгируемый объект или переменная
     * @param string $comment    необязательный комментарий 
     * @param bool $returnOnly   возвращать (true) или просто выводить на экран
     * @param int $newLinesBeforeCount Число переносов строк "до" 
     * @param int $newLinesAfterCount  Число переносов строк "после"
     * @return string/null  -- в зависимости от  значения $returnOnly
     */
    public static function pre($var, $comment = '', $returnOnly = false, 
            $newLinesBeforeCount = 0, $newLinesAfterCount = 1) 
    {
        if (!self::$log) { return; }
        
        $comment = $comment ? ($comment .= ':') : '';
        if (self::$log) {    
            if ($returnOnly) {
                return $comment . self::preStartSymbol() . print_r($var, true) . self::preEndSymbol();
            } else {
                echo  self::getNewLinesSymbolsGroup($newLinesBeforeCount) 
                        . $comment 
                        . self::preStartSymbol() 
                        . print_r($var, true) 
                        . self::getNewLinesSymbolsGroup($newLinesAfterCount);
            }
        }
    }
    
    /**
     * Обёртка над
     *  ::pre($var, $comment) + die() 
     * 
     * @param mixed  $var        логгируемый объект или переменная
     * @param string $comment    необязательный комментарий 
     * @param bool $returnOnly   возвращать (true) или просто выводить на экран
     */
    public static function pred($var, $comment = '', $returnOnly = false)
    {
        if (!self::$log) { return; }
        self::pre($var, $comment, $returnOnly);
        die();
    }
    
    
    
    /**
     * Печатает тэг hr
     */
    public static function hr()
    {   
        if (!self::$log) { return; }
        echo '<hr>';
    }
    
    
    /**
     *  Распечатывает первое, если оно не массив, или второе в обратном случае
     * 
     * @param mixed $firstValue
     * @param mixed $secondValue
     */
    public static function echoFirstOrSecondIfFirstIsArray($firstValue, $secondValue) 
    {
        if (!self::$log) { return; }
        if (is_array($firstValue)) {
           echo ($secondValue);
        } else {
           echo ($firstValue); 
        }
    }
    
}