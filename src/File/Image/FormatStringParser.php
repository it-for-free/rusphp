<?php

namespace ItForFree\rusphp\File\Image;

/**
 * Разбирает строку формата и возвращает запрашиваемые параметры изображения
 */
class FormatStringParser
{
    
    /**
     * Разберёт строку и вернёт парамерты преобразования изображения/
     * 
     * Входящяя строка должны быть в формате:
     * 
     * [ширина]x[высота]x[Sточная обрезка?]x[позиция обрезанного участка]x[подгон_максимального_разрешения_под_прорции]
     * 
     * @param string $formatString  например (максимум опций): 125x125xSxCxP (минимум нужны длина и ширина: 125x125)
     * @return array
     */
    public static function getParams($formatString) 
    {
        $imgParams = array();
        
        // Определим параметры обрезки, разбрав стоку формата
        $formatFlags     = explode("x", $formatString);
        $imgParams['width']    = $formatFlags[0];
        $imgParams['height']   = $formatFlags[1];
        $imgParams['strong']   = (isset($formatFlags[2]) && strtolower($formatFlags[2]) == 's') ? true : false;
        /**
        * b - снизу
        * t - сверху
        * другое/отсутствие - центр
        */
        $imgParams['position'] = (isset($formatFlags[3]) && strtolower($formatFlags[3]) == 'b') ? 2 : 
            ((isset($formatFlags[3]) && strtolower($formatFlags[3] == 't')) ? 1 : 0); // используйте "C" для позиционирования по центру
        
        /**
         * Для использвоания этого параметра обязательно укажите предыдущий параметр позиции обрезки, если по умолчанию то так: 125x125xSxCxP
         */
        $imgParams['proportionalOnlyWithResolution'] =   // может нужно просто подогнать под соотношения сторон, сохранив максимальное разрешение
                (isset($formatFlags[4]) && strtolower($formatFlags[4]) == 'p') ? true : false;
        
        
        return $imgParams;
        
    }
}
