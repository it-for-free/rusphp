<?php


namespace ItForFree\rusphp\PHP\Str;
use ItForFree\rusphp\PHP\Str\Str;

/**
 * Фильтрация подстрок из строки
 * (html tags, protocols, теги, протоколы)
 *
 */
class StrFilter 
{   
    /**
     * Удалит все html теги (если не указаны разрешенные) 
     * + все протоколы (которые встречаются в начале интернет ссылок url)
     * 
     * @param string $text  строка, к корой применяется фильтр
     * @param string[] $allowedTags  массив разрешеннных тегов
     * @return string
     */
    public static function tagsAndProtocols($text, $allowedTags = [])
    {
        $result = strip_tags($text, implode('', $allowedTags));
        $result = Str::replaceSubStrs($result, ['https://', 'http://', 'ftp://']);
        return $result;
    }
}
