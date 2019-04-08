<?php

namespace ItForFree\rusphp\PHP\ArrayLib\ArrNestedElement;

use ItForFree\rusphp\PHP\Str\StrCommon;
use ItForFree\rusphp\PHP\Comparator\Compare;

/**
 * Работа с элементами массива "по пути" на произвольном уровне вложенности.
 * Многомерные массивы, точечная нотация (поддерживает произвольные разедлители).
 */
class ArrNestedElement 
{
    /**
     * @var string Путь к элементумассива в точечной или иной нотации см. @see http://fkn.ktu10.com/?q=node/10815  
     */
    protected $path = '';
    
    /**
     * @var srting разлитель сегментов пути 
     */
    protected $delimiter = '.';
    
    /**
     * @var string сегменты пути: подразумевается, что каждый из них является ключом массива на своем уровне вложенности 
     */
    protected $segments = '.';
    
    /**
     * @var bool применять ли строгое сравнение, напр. при поиске элемента в массиве
     */
    protected $strongCompare = false;
    
    /**
     * объект описывающий путь к эелменту массива 
     * 
     * @param type $path
     * @param type $delimiter
     * @param type $strongCompare
     */
    public function __construct($path, $delimiter = '.', $strongCompare = false) 
    {
        $this->path = $path;
        $this->delimeter = $path;
        $this->strongCompare = $strongCompare;
        
        $this->segments = explode($delimiter, $path);
    }
    
    /**
     * Извлекает элемент массива по пути, заданному при создании данного объекта 
     *  @see http://fkn.ktu10.com/?q=node/10815
     * 
     * @param array $arr  массив
     * @return mixed
     */
    public function get($arr)
    {
        if (StrCommon::isInStr($this->path, $this->delimiter)) {
            $value = $arr;
            foreach ($this->segments as $levelName) {
                $value = $value[$levelName];
            }
        } else {
            return $arr[$this->path];
        }
        
        return $value;
    }
    
    /**
     * Реализация in_array, но при условии, что просматривается не элементы массива, вложенные к них значения,
     * лежащие по определенному пути
     * 
     * @param array $arr    массив в котором ищем
     * @param mixed $value  значение, которое ищем
     * @return boolean
     */
    public function inArray($arr, $value)
    {
        $result  = false;
        foreach ($arr as $val) {
           $nestedValue  = $this->get($val);
           if (Compare::eq($nestedValue, $value, $this->strongCompare)) {
              $result  = true; 
              break;
           }
        }
        return $result;
    }
}