<?php

namespace ItForFree\rusphp\PHP\Str;

/**
 * Общая работа со строками
 *
 */
class StrCommon
{
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
     * @param array|string $subStrArr  массив подстро или порсто одна подстрока
     * @return boolean
     */
    public static function isOneFromArrHere($str, $subStrArr) {
        $result  = false;

        if (!is_array($subStrArr)) {
            return self::isInStr($str, $subStrArr);
        }

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
     * @param string $str        строка, в которой производятся замены
     * @param array $substrs     какие подстроки заменять
     * @param string $newSubStr  на что заменять
     * @return type
     */
    public static function  removeSubStrs($str, $substrs, $newSubStr = '')
    {
        $result = $str;

        foreach ($substrs as $val)
        {
            $result = str_replace($val, $newSubStr, $result);
        }

        return $result;
    }

    /**
     * Вернёт последний символ строки
     *
     * @param  string $str
     * @param  string $encoding  (не обязательно) кодировка как для mb_substr(). По умолчанию берётся из mb_internal_encoding()
     * @throws \Exception
     */
    public static function  getLastSymb($str, $encoding = null)
    {
        if (!empty($str)) {
            if ($encoding) {
                return mb_substr($str, -1, null, $encoding);
            } else {
                return mb_substr($str, -1);
            }
        } else {
            throw new \Exception('Your String is Empty! Its impossible to get last symbol');
        }
    }

    /**
     * Получит первый символ строки
     *
     * @param  string $str
     * @param  string $encoding  (не обязательно) кодировка как для mb_substr()
     * @return char
     */
    public static function  getFirstSymbol($str, $encoding = null)
    {
        if ($encoding) {
           return mb_substr($str, 0, 1, $encoding);
        } else {
           return mb_substr($str, 0, 1);
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
     * Заменит строку на значение из массива, ключом которого является данная строка
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
     * Заменит подстроку в конце данной строки (если таковая найдётся)
     *
     * @param string $sourceStr строка, в которой надо провести замену
     * @param string $oldSubStr что заменять
     * @param string $newSubStr на что заменять (не обязателен. Если хамена на пустую строку)
     */
    public static function  replaceSubStrInTheEnd($sourceStr, $oldSubStr, $newSubStr = '')
    {
        return preg_replace('/'. preg_quote($oldSubStr, '/')
            . '$/', $newSubStr, $sourceStr);
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
    public static function explodeByBR($str) 
    {
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
    
    /**
     * Проверит оканчивается (заканчивается) ли строка данной подстрокой
     * 
     * @param string $string    строка 
     * @param string $subString подстрока
     * @return boolean
     */
    public static function isEndWith($string, $subString) 
    {
        $strlen = strlen($string);
        $testlen = strlen($subString);
        
        if ($testlen > $strlen) { 
            return false;
        }
        
        return substr_compare($string, $subString,
            $strlen - $testlen, $testlen) === 0;
    }
    
    /**
     * Проверит, что это
     *  не пустая строка 
     * и не null
     * 
     * @param mixed $value   строка или число
     * @return boolean
     */
    public static function isEmptyStr($value)
    {
        $result  = ($value === '') || is_null($value);
        return $result;
    }
    
    /**
     * Проверит что строка состоит только из пробельных символов 
     * (или вообще пуста)
     * - в основе лежит применение стандартной trim()
     * 
     * @param mixed $value   строка или число
     * @return boolean
     */
    public static function isSpaceOnly($value)
    {
        $trimed = trim($value);
        return static::isEmptyStr($trimed);
    }
}
