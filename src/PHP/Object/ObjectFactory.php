<?php

namespace ItForFree\rusphp\PHP\Object;

use ItForFree\rusphp\PHP\Object\ObjectClass\Constructor;

/**
 * Для порождения объектов
 *
 */
class ObjectFactory {
   
   /**
    * Вернёт экземпляр класса -- или обычный объект или "одиночку" 
    * (по паттерну Singletone)
    * 
    * @param string $className   Имя класса
    * @param string $singletoneInstanceAccessStaticMethodName  необязательное имя статического метода для доступа к объекту-одиночке. По умолчанию 'get'
    * @return object
    */
   public static function getInstanceOrSingletone($className, 
        $singletoneInstanceAccessStaticMethodName = 'get')
   {
       $result = null;
       if (Constructor::isPublic($className)) {
          $result = new $className;
       } else {
            $result =  call_user_func($className . '::' 
                . $singletoneInstanceAccessStaticMethodName); 
       }
       
       return $result;
   }
   
}
