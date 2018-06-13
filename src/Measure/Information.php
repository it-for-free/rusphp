<?php

namespace rusphp\Measure;

use rusphp\PHP\Str\StrCommon as Str;
use rusphp\Log\SimpleEchoLog as log;

/**
 * Конвертер единиц измерения информации
 */
class Information
{
    /**
     * @var array  условные обозначения единиц измерения + степень 1024^x -- на это число надо умножить чтобы получить байты  
     */
    private static $conventionLetters = [
        'B' => 0,  // байты
        'K' => 1,  // килобйты
        'M' => 2,  // мегабайты
        'G' => 3   // байты
    ];
    
    /**
     * Стандартный множитель -- "кило"
     * @var int
     */
    private static $base = 1024; 
    
    /**
     * До какого знака округлять при конвертировании (чтобы не таскать бесконечные дроби)
     * 
     * @var int
     */
    public static $roundPow =  3;
    
    /**
     * Разбирает сторку и вернёт объём памяти в байтах
     * 
     * Примеры передаваемой строки:
     * 128M  -- мегабайты
     * 25G   -- гигабайты  
     * 512K   -- килобайты 
     * 
     * @param  string $infoCountStr
     * @return int
     */
    public static function fromString($infoCountStr)
    {
        $result = false;
        
        $letters = array_keys(self::$conventionLetters);
        $conventions = self::$conventionLetters;
        
        $numb = Str::removeSubStrs($infoCountStr, $letters);
        $last = Str::getLastSymb($infoCountStr);
 
        foreach ($conventions as $letter => $pow) {
            if ($letter == $last) {
                $result = $numb * pow(self::$base, $pow);
                break;
            }
        }
        
        if ($result === false) {
            throw new \Exception('Cant understand string format!');
        }
        
        return $result;
    }
    
    /**
     * Псведоним для self::fromString()
     * Вернёт размер в байтах
     * 
     * @param  string $countStr
     * @return int
     */
    public static function getFromStrInBytes($countStr)
    {
        return self::fromString($countStr);
    }
    
    /**
     * Из байтов в мегабайты
     */
    public static function Mb($bytes)
    {
        return round($bytes / pow(self::$base, self::$conventionLetters['M']), self::$roundPow); 
    }
    
    
    /**
     * Приведёт переданное значение в байтах к нужному типу
     * 
     * @param int $bytes
     * @param string $measureCode -- одна из букв $conventionLetters
     * @return int 
     * @throws \Exception
     */
    public static function convert($bytes, $measureCode)
    {
        $result = '';

        switch ($measureCode) {
            case 'B':
                $result = $bytes;
                break;
            case 'M':
                $result = self::Mb($bytes);
                break;
            default:
                throw new \Exception("Unknown measure type code! --$measureCode-- !");
        }
        
        return $result;
    }
    
    /**
     *  Число объёма информации как строка с буквой на конце
     * 
     * @param string $bytes
     * @param string $measureCode -- одна из букв $conventionLetters
     * @return string
     */
    public static function t($bytes, $measureCode)
    {
        return self::convert($bytes, $measureCode) . $measureCode;
    }
}