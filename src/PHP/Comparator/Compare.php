<?php


namespace ItForFree\rusphp\PHP\Comparator;

/**
  Обертки для различных сравнений элементов 
 * 
 * Для удобства класс назван глаголом, а методы могут быть существительными
 *
 */
class Compare 
{
   public static function eq($val1, $val2, $strong = false)
   {
       if ($strong) {
           return ($val1 === $val2);
       } else {
           return ($val1 == $val2);
       }
   }
}
