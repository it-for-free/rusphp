<?php

namespace ItForFree\rusphp\PHP\Regexp;

/**
 * Набор готовых регулярных выражений для различных проверок
 * ((php regexp examples))
 * 
 * @author qwe
 */
class RegexpExamples {
   
    /**
     * Имя человека (фамилия или отчество), 
     * просто отсекаем недопустимые символы печатные символы
     * ((regexp for human name ))
     */
    const humanName = '/^[^<,.\"@\/{}()*$%?=>:|;#]*$/i';
    
}
