<?php

namespace ItForFree\rusphp\PHP\Object;

use ItForFree\rusphp\PHP\Object\Exception\CountException;
use ItForFree\rusphp\PHP\Object\Exception\TypeException;
use ItForFree\rusphp\PHP\Object\ObjectClass\Constructor;
use ReflectionException;

/**
 * Для порождения объектов
 *
 */
class ObjectFactory {

    /**
     * 
     * @var type ReflectionMethod
     * Содержит в себе имя конструктора и наименование класса
     */
    private static $classConstruct;
            
    /**
     * 
     * @var type Array
     * Массив параметров конструктора 
     */        
    private static $constructorParams;
           
    /**
     * 
     * @var type Array
     * Массив, хранящий в себе параметры, для которых в конструкторе есть значения по умолчанию 
     */    
    private static $constructorOptionalParams;
       
    /**
     * 
     * @var type Array
     * Массив, содержащий типы передаваемых переменных
     */
    private static $constructorData;

   /**
    * Вернёт экземпляр класса -- или обычный объект или "одиночку" 
    * (по паттерну Singletone)
    * 
    * @param string $className   Имя класса
    * @param string $singletoneInstanceAccessStaticMethodName  необязательное 
    *   имя статического метода для доступа к объекту-одиночке. По умолчанию 'get'
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
     * 
     * @param string $classname имя класса для создания объекта
     * @param array $config массив аргументов для конструктора
     * Порядок следования аргументов не обязательно должен совпадать с порядком, который ожидает конструктор.
     * Если массив ассоциативный, то он должен быть вида [propertyName => propertyValue]
     * Если массив вида [0, 10, 'string'],
     * а конструктор ожидает sting, int, int ему будут переданы string, 0, 10 входного массива
     * @return null|object
     * 
     * @throws CountException
     * @throws TypeException
     * @throws ReflectionException
     */
    public static function createObjectByConstruct(string $classname,
       array $config = []): ?object
    {
       $resultObject = null;
       self::cleanProperties();
       if (Constructor::isPublic($classname)) {
           $class = new \ReflectionClass($classname);
           self::$classConstruct = $class->getConstructor();
           self::extractConstructor();
           $sorted = self::sortArgs($config);
           self::checkCorrect($sorted);
           $resultObject = $class->newInstanceArgs($sorted);
       }

       return $resultObject;
    }

    /**
     * Извлекает данные из конструктора и устанавливает тип параметров конструктора по type hinting.
     * Если type hinting не указан, то считается, что тип равен null,
     * что позволяет получать на вход любой тип данных
     */ 
     private static function extractConstructor()
    {
       $params = self::$constructorParams = self::$classConstruct->getParameters();
       self::$constructorData = [];
       foreach (self::$constructorParams as $param) {
           $constructParamType = $param->getType() ? $param->getType()->getName() : null;
           $constructorPropertyName = $param->getName();
           self::$constructorData[$constructorPropertyName] = $constructParamType;
           if ($param->isDefaultValueAvailable() === true) { //->isOptional() не всегда работает
               self::$constructorOptionalParams[$param->getName()] = $param->getDefaultValue();
           }
       }
    }

    /**
     * Расставляет элементы $config в понятном для конструктора порядке.
     * 
     * @param array $config
     * @return array
     * 
     * @throws CountException
     * @throws TypeException
     */
    private static function sortArgs(array $config): array
    {
       $sorted = [];
       $i = 0;
       foreach (self::$constructorData as $propertyName => $propertyType) {
           //$config - ассоциативный массив
           if (array_key_exists($propertyName, $config)) {
               $sorted[$propertyName] = $config[$propertyName];
           } else {
               //$config - не ассоциативный массив, находим свойства просто по порядку
               if (array_key_exists($i, $config)) {
                   $sorted[$propertyName] = $config[$i];
               }
           }
           $i++;
       }
       return $sorted;
    }


    /**
     * Проверяет отсортированный массив на соответствие типов и количества аргументов
     * 
     * @param $sorted array
     * @throws TypeException
     * @throws CountException
     */
   private static function checkCorrect(array $sorted)
   {
       self::checkCountCorrect($sorted);
       self::checkTypeCorrect($sorted);
   }

    /**
     * Проверяет соответствие переданных типов и ожидаемых конструктором
     * 
     * В случае, если количество переданных типов не соответствует ожидаемому, бросается исключение 
     * 
     * @param $sorted array
     * @throws TypeException
     */
    private static function checkTypeCorrect(array $sorted)
    {
        $sortedTypes = self::getSortedTypes($sorted);
         foreach (self::$constructorData as $paramName => $paramType) {
                 if (array_key_exists($paramName, $sortedTypes)) {
                     if (!is_null($paramType) && $paramType !== 'mixed' && $paramType !== $sortedTypes[$paramName]) {
                        throw new TypeException();
                     }                   
                 }
         }
    }

    /**
     * Определяет типы переданных аргументов.
     * 
     * Приводит их к синтаксису описания типов в конструкторе.
     * Подробнее в описании self::getType
     * 
     * @param $sorted array
     * @return array
     */
    private static function getSortedTypes(array $sorted): array
    {
        $res = [];
        foreach ($sorted as $name => $value) {
            $res[$name]= self::getType($value);
        }
        return $res;
    }

    /**
     * Проверяет количество переданных параметров с количеством обязательных
     * 
     * Количество обязательных аргументов конструктора
     * должно быть равно количеству отсортированных элементов из sorted
     * 
     * @param $sorted
     * @throws CountException
     */
    private static function checkCountCorrect(array $sorted)
    {
       $countNeeded = count(self::$constructorParams) - count(self::$constructorOptionalParams);

       $params = array_keys($sorted);
       $countSorted = count($sorted);

       foreach ($params as $param) {
           //если параметр в sorted не обязательный, его не считаем
           if (array_key_exists($param, self::$constructorOptionalParams)) {
               $countSorted--;
           }
       }

       if ($countNeeded !== $countSorted) {
           throw new CountException();
       }
    }

    /**
     * Возвращает тип переданного значения
     * 
     * Функция gettype для типа integer вернёт тип integer.
     * Но в свойствах конструктора тип integer называется int,
     * boolean - bool
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
     * 
     * Возвращает объект, заполненный переданными параметрами 
     * 
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
   
   /**
    * Очистит статические свойства класса 
    */
    private static function cleanProperties()
    {
       self::$classConstruct = array();
       self::$constructorData = array();
       self::$constructorOptionalParams = array();
       self::$constructorParams = array();
    }
}