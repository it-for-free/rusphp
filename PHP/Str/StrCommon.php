<?php

namespace ItForFree\rusphp\PHP\Str;


/**
 * Общая работа со строками
 *
 */
class StrCommon {
    
    /**
     * Проверит, что первая строка начинается со второй
     * 
     * @param string $str      основная строка
     * @param string $substr   та, которая может содержаться внутри основной
     */
    public static function isStart($str, $substr)
    {
        $result = strpos($str, $substr);
        if ($result === 0) { // если содержится, начиная с первого символа
          return true;
        } else {
          return false; 
        }
    }
 
    /**
     * Проверить вхождение подстроки в строку
     * 
     * @param type $str
     * @param type $substr
     * @return boolean
     */
    public static function  isInStr($str, $substr)
     {
       $result = strpos ($str, $substr);
       if ($result === FALSE) // если это действительно FALSE, а не ноль, например 
         return false;
       else
         return true;   
     }
     
    /**
     * Проверт входит ли хотя бы одна строка из массива в данную стороку
     * 
     * @param string $str       строка, в которой осуществлять поиск
     * @param array $subStrArr  массив подстрок
     * @return boolean
     */
    public static function isOneFromArrHere($str, $subStrArr) {
        $result  = false;
        
        foreach ($subStrArr as $substr) {
            if (self::isInStr($str, $substr)) {
               $result = true;
               break;
            }
        }
        
       return $result;
    }
     
     
    /**
     * Заменит все указанные в массиве подстроки на указанную подстроку
     * Например: удалит все указанные подстроки из строки
     * 
     * @param string $str
     * @param array $substrs
     * @param string $newSubStr
     * @return type     
     */
    public static function  removeSubStrs($str, $substrs, $newSubStr = '')
    {
        $result = $str;
        
        foreach ($substrs as $key => $val)
        {
            $result = str_replace($val, $newSubStr, $result);
        }
        
        return $result;
    }
    
    /**
     * Вернёт последний символ строки
     * 
     * @param  string $str
     * @return char
     * @throws \Exception
     */
    public static function  getLastSymb($str)
    {
        if (!empty($str)) {
            return substr($str, -1);
        } else {
            throw new \Exception('Your String is Empty! Its impossible to get last symbol');
        }
    }
    
    
    /**
     * (псевдоним)
     * 
     * Заменит все указанные в массиве подстроки на указанную подстроку
     * Например: удалит все указанные подстроки из строки
     * 
     * @param string $str
     * @param array $substrs
     * @param string $newSubStr
     * @return string     
     */
    public static function  replaceSubStrs($str, $substrs, $newSubStr = '')
    {
        return self::removeSubStrs($str, $substrs, $newSubStr = '');
    }
    
    
    /**
     * Заменит строку на значение из массима, ключом которого является данная строка
     * 
     * @param string $str
     * @param array $keyOldAndNewValues = ['old' => 'new', 'old2' => 'new2']
     * @return string
     */
    public static function replaceIfInArray($str, $keyOldAndNewValues)
    {
        $result = $str;
        
        if (isset($keyOldAndNewValues[$str]))
        {
            $result = $keyOldAndNewValues[$str];
        }
        
        return $result;
    }
    
    /**
     * Сравнит две строки относительно алфавита -- алфавитный порядок
     *  
     * @param string $str1
     * @param string $str2
     * @return int          1 если первая "больше", -1 если меньше, 0 если равны
     */
    public static function compareAsInAlphbet($str1, $str2)
    {
        return strnatcasecmp($str1, $str2);
    }
    
    /**
     * Разделит строку в массив по тегу <br> (переноса строки)
     * 
     * @see http://stackoverflow.com/questions/20196103/how-to-explode-a-string-on-br-tag
     * 
     * @param string $str
     * @return array
     */
    public static function explodeByBR($str) {
        $brRegExp = '/<br[^>]*>/i';
        $arr = preg_split($brRegExp, $str);
        
        return $arr;
    }
    
    /**
     * Переводит все символы строки в верхний регистр
     * 
     * @param  string $str
     * @return string
     */
    public static function up($str)
    {
        return strtoupper($str);
    }
    
    /**
     * Запишет содержимое файла в строку
     * 
     * @param string $filePath  путь к файлу
     * @return string
     */
    public static function fromFile($filePath)
    {
        $result = false;
        
        $fdata = fopen($filePath, "r");
        if ($fdata) // если удалось открыть файл
        { // то читаем данные из него
            $result = fread($fdata, filesize($filePath));
            fclose($fdata); //закрываем файл
        }
        
        return $result;
    }
}
