<?php

namespace ItForFree\rusphp\Html;

/**
 * Класс для печати тэгов 
 * -- через echo()
 */
class Tag
{
    /**
     * Распечатает элемент гиперссылки (a href)
     * 
     * @param string $hrefUrl    значение атрибута href
     * @param string $text       текст ссылки  
     * @param string $attributes значения остальных атрибутов
     */
    public static function a($hrefUrl, $text = '', $attributes = [])
    {
        $attributes['href'] = $hrefUrl;
        return self::getHtml($attributes, $text);
    }
    
    /**
     * Распечатает указанный тэг
     * 
     * @param string $tagName
     * @param string $attributes
     */
    public static function printTag($tagName, $attributes = [])
    {
        echo self::getHtml($tagName, $attributes);
    }
    
    /**
     * Вернёт html для указанного тега
     * 
     * @param string $tagName
     * @param array $attributes ассоцитивный массив имен и значений атрибутов
     *  @param string $content  содержимое блока тега
     * @return type
     */
    public static function getHtml($tagName, $attributes = [], $content = '')
    {
        $result = '';
        $result .= "<$tagName ";
        foreach ($attributes as $key => $value) {
            $result .= " $key='$value' ";
        } 
        $result .= ">$content</$tagName>";
        
        return $result;
    }
}

