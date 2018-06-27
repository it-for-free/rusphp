<?php

namespace ItForFree\rusphp\PHP\Object\ObjectClass;
/**
 * Для работы с конструктором класса
 *
 */
class Constructor {
   
   /**
    * Проверит является ли конструктор данного класса публичным
    * 
    * @param  string $className
    * @return bool  false если конструктор непубличен или вообще неописан
    */
   public static function isPublic($className)
   {
       $result = true; //если без конструктора, то экземплярвсё же можно создать
       
       if (method_exists($className, '__construct')) {
            $constructor = new \ReflectionMethod($className, '__construct');
            $result = $constructor->isPublic();
       }
       
       return $result;
   }    
   
}
