<?php

namespace ItForFree\rusphp\Network\Domain;


/**
 * Для получения адреса (домена, на который надо ставить куки)
 */
class CookieDomain extends Domain 
{
   
   /**
    * Отдаст куки-домен для текущего домена,
    *  в соответствии с переданными параметрами
    * 
    * @param int $minusLevel       насколько меньший уровень домена нужен, 
    * например, для  $minusLevel =1 и домена abc.edf.example.com вернется  edf.example.com
    * 
    * @param int $minimumBaseLevel  минимальный уровень домена, который должен 
    * получится в итоге (по умолчанию 1 т.е. может быть равен, например ru, 
    * если передать 2 то, минимальным будет уже site.ru), если получившийся 
    * поддомен меньше минимального, то просто вернётся текущий поддомен $this->name
    * 
    * @param bool $startWithDot добавлять ли точку в начале (ТОЛЬКО в случае,
    *  если куки-домен не совпадает с текущим -- т.е. оказался в следствии
    * $minusLevel более высокого уровня )
    * 
    * @return string
    */
   public function get($minusLevel = 0, $minimumBaseLevel = 1, $startWithDot = true)
   {
       $base = $this->getBase($minusLevel, $minimumBaseLevel);
       
       
       if (!($base == $this->name) && $startWithDot) {
           $base = '.' . $base;
       }
//       pdie($base);
       
       return $base; 
   }
}
