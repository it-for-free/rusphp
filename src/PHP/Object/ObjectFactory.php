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
     * @throws \ReflectionException
     */
   public static function createObjectByConstruct(string $classname,
       array $config = []): ?object
   {
       $resultObject = null;
       $sorted = [];
       if (Constructor::isPublic($classname)) {
           $class = new \ReflectionClass($classname);
           $classConstruct = $class->getConstructor();
           $constructParams = $classConstruct->getParameters();
           foreach ($constructParams as $param) {
               $constructParamType = $param->getType()->getName();
               foreach ($config as $property => $value) {
                   $type = self::getType($value);
                   if ($constructParamType === $type) {
                       $sorted[$property] = $value;
                       unset($config[$property]);
                   }
               }
           }

           $resultObject = $class->newInstanceArgs($sorted);
       }

       return $resultObject;
   }

    /**
     * @param null $value
     * @return false|string
     */
    private static function getType($value = null)
    {
        if (is_object($value)) {
            $type = get_class($value);
        } else {
            $type = gettype($value);
        }
        switch ($type) {
            case 'integer' :
                $type = 'int';
                break;
            case 'boolean' :
                $type = 'bool';
                break;
            case 'double' :
                $type = 'float';
                break;
        }

        return $type;
    }



    /**
     * Универсальный сеттер
     * @param $object
     * @param array $data ассоциативный массив вида ['propertyName' => propertyValue]
     * @return object
     */
   public static function setPublicParams($object, array $data =[]): object
   {
       if (!empty($data)) {
           foreach ($data as $property => $value) {
               $object->$property = $value;
           }
       }

       return $object;
   }
}