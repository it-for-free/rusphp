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
   
   /**
    * @param string $classname
    * @param array $config
    * @return null|object
    */
   public static function createObjectByConstruct(string $classname,
       array $config = [])
   {
       $resultObject = null;
       if (Constructor::isPublic($classname)) { 
           $reflection = (new \ReflectionClass($classname))
               ->newInstanceArgs($config);
       }
       return $resultObject;
   }

    /**
     * @param $object
     * @param array $params
     * @return object
     */
   public static function setPublicParams($object, array $params =[]): object
   {
       if (!empty($params)) {
           foreach ($params as $param) {
               $object->$param = '';
           }
       }

       return $object;
   }
}