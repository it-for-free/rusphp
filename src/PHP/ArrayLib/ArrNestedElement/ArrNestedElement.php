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
     * @var srting разделитель сегментов пути 
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
     * @param array $arr  массив, в котором исковый эелмент елжит по описываему данным объектом пути
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
     * Установит значение по пути, который описано в данном объекте
     * 
     * @param array $arr    массив в котором ищем нужный эелмент для перезаписи
     * @param mixed $value  значение, которое запишем по нужному пути в массиве
     * @return array   массив с замененным значением
     */
    public function set($arr, $value)
    {
        $link = &$arr;
        foreach($this->segments as $key) {
            $link = &$link[$key];
        }
        $link = $value; // устанавливаем новое значение
        
        return $arr;
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
    
    /**
     * Применит обработчик к подэлементу, соответствующему данному объекту
     * 
     * @param callable $handler
     * @param array $arr    массив, в каждому элементу которого надо применить функцию
     * @return array
     */
    public function applyHandler($handler, $arr)
    {
        $newSubvalue = $handler($this->get($arr));
        return $this->set($arr, $newSubvalue);
    }
    
    /**
     * Реализация array_map для вложенного элемента массива:
     *  колбек применяется не к элементу массива,
     *  а к его подэлементу, путь к которому и описывается данным объектом)
     * 
     * @param callable $handler  функция-обработчик
     * @param array $arr    массив, в каждому подэлемента элементу которого надо применить функцию
     * @return array
     */
    public function arrayMap($handler, $arr)
    {
        $modifiedArray = [];
        foreach ($arr as $key => $val) {
            $modifiedArray[$key] = $this->applyHandler($handler, $val);
        }
        return $modifiedArray;
    }
    
    /**
     * Ну ли производить сравнение в строгом режиме
     * 
     * @return bool true  если в тсрогом, false если в нестрогом
     */
    public function isCompareStrong()
    {
        return $this->strongCompare;
    }
}