<?php

namespace ItForFree\rusphp\PHP\Object;

use ItForFree\rusphp\PHP\Object\Exception\TypeException;
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
       if (Constructor::isPublic($classname)) {
           $class = new \ReflectionClass($classname);
           $classConstruct = $class->getConstructor();
           $constructorData = self::extractConstructor($classConstruct);
           $sorted = self::sortArgs($constructorData, $config);
           $resultObject = $class->newInstanceArgs($sorted);
       }

       return $resultObject;
   }

    /**
     * Извлекает данные из конструктора в виде
     * [propertyName => propertyType]
     *
     * @param $classConstruct
     * @return array
     */
   private static function extractConstructor($classConstruct): array
   {
       $constructParams = $classConstruct->getParameters();
       $constructorData = [];
       foreach ($constructParams as $param) {
           $constructParamType = $param->getType()->getName();
           $constructorPropertyName = $param->getName();
           $constructorData[$constructorPropertyName] = $constructParamType;
       }

       return $constructorData;
   }

    /**
     * Расставляет элементы $config в понятном для конструктора порядке.
     * @param array $constructorData
     * @param array $config
     * @return array
     */
   private static function sortArgs(array $constructorData, array $config): array
   {
       $sorted = [];
       $i = 0;
       foreach ($constructorData as $propertyName => $propertyType) {
           //$config - ассоциативный массив
           if (array_key_exists($propertyName, $config)) {
               if (self::isTypeEquals($propertyType, $propertyName, $config) === true) {
                   $sorted[$propertyName] = $config[$propertyName];
                   unset($config[$propertyName]);
               }

           } else {
               if (array_key_exists($i, $config)) {
                   if (self::isTypeEquals($propertyType, $i, $config) === true) {
                       $sorted[$propertyName] = $config[$i];
                       unset($config[$i]);
                   } else {
                       throw new TypeException();
                   }
               }


           }
           $i++;
       }

       return $sorted;
   }

    /**
     * @param $propertyType
     * @param $index
     * @param $config
     * @return bool
     */
   private static function isTypeEquals($propertyType, $index, $config)
   {
       $value = $config[$index];
       $type = self::getType($value);
       return $type === $propertyType;
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