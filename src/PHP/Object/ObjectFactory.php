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
     * Создает объект через конструктор.
     * @param string $classname имя класса для создания объекта
     * @param array $config массив аргументов для конструктора
     * Порядок следования аргументов не обязательно должен совпадать с порядком, который ожидает конструктор.
     * Если массив ассоциативный, то он должен быть вида [propertyName => propertyValue]
     * Если массив вида [0, 10, 'string'],
     * а конструктор ожидает sting, int, int ему будут переданы string, 0, 10 входного массива
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
           $constructorData = [];
           foreach ($constructParams as $param) {
               $constructParamType = $param->getType()->getName();
               $constructorPropertyName = $param->getName();
               $constructorData[$constructorPropertyName] = $constructParamType;
           }
           //ключи ассоциативного массива, переданного в метод, содержат названия всех аргументов,
           // которые ожидает конструктор
           if (self::isArrayKeysEqual($constructorData, $config) === true) {
               foreach ($constructorData as $propertyName => $propertyType) {
                   $value = $config[$propertyName];
                   $type = self::getType($value);
                   if ($type === $propertyType) {
                       $sorted[$propertyName] = $value;
                       unset($config[$propertyName]);
                   }
               }
           } else {
               //$config - не ассоциативный. разрулим все на основе сравнения типов
               foreach ($constructorData as $propertyName => $propertyType) {
                   foreach ($config as $property => $value) {
                       $type = self::getType($value);
                       if ($propertyType === $type) {
                           $sorted[$property] = $value;
                           unset($config[$property]);
                       }
                   }
               }
           }
           $resultObject = $class->newInstanceArgs($sorted);

           }

       return $resultObject;
   }

    /**
     * Сравнивает ключи двух массивов.
     * Вернёт true, если ключи одного массива равны ключам другого
     * не зависимо от порядка их следования
     * @param $first array первый массив
     * @param $second array второй массив
     * @return bool
     */
   private static function isArrayKeysEqual(array $first, array $second): bool
   {

       $keysFirst = array_keys($first);
       $keysSecond = array_keys($second);
       asort($keysFirst);
       asort($keysSecond);
       return ($keysFirst === $keysSecond);
   }

    /**
     * Функция gettype для типа integer вернёт тип integer.
     * Но в свойствах конструктора тип integer называется int.
     *
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
            //gettype для float по историческим причинам возвращает double
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