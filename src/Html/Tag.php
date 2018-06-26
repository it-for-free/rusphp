<?php

namespace ItForFree\rusphp\Html;

/**
 * Класс для печати тэгов 
 * -- через echo()
 */
class Tag
{
    /**
     * РАспечатает элемент гиперссылки (a href)
     * 
     * @param string $hrefUrl  значение атрибута href
     * @param string $attributes значения остальных атрибутов
     */
    public static function a($hrefUrl, $attributes = [])
    {
        $attributes['href'] = $hrefUrl;
        self::printTag($attributes);
    }
    
    /**
     * Распечатает указанный тэг
     * 
     * @param string $tagName
     * @param string $attributes
     */
    public static function printTag($tagName, $attributes = [])
    {
        echo "<$tagName ";
        foreach ($attributes as $key => $value) {
            echo " $key='$value' ";
        } 
        echo "></$tagName>";
    }
  

}

