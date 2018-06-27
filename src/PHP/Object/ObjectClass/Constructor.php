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
    * @return bool
    */
   public static function isPublic($className)
   {
       $constructor = new \ReflectionMethod($className, '__construct');
       return $constructor->isPublic();
   }    
   
}
