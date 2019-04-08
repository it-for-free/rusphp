<?php

namespace ItForFree\rusphp\PHP\ArrayLib;

use ItForFree\rusphp\PHP\ArrayLib\ArrPath;

use ItForFree\rusphp\PHP\ArrayLib\ArrNestedElement\ArrNestedElement;


/**
 * Для сортировки массивов
 */
class ArrSort {
    
    /**
     * Отсортирует часть массива по порядку, указанному в массиве-шаблоне 
     * (если элементы из шаблона встретятся), при этом ключи будут сохранены 
     * (хотя порядок следования элементов в общем случае должен измениться)
     * 
     * @param array $arr  исходный массив
     * @param array $rightOrderedValues  шаблон (в этом массива значения выстроены в нужном порядке)
     * @param callable $handleOriginal     обработчик для элемента исходного массив (напр. использующий trim())
     * @param callable  $handleOrderedValues   обработчик для элемента шаблона (напр. использующий trim())
     * @param bool $addToStart           добавлять ли в начало итогового массива (по умолчанию), или в конец (если передано false)
     * @return array
     * 
     */
    public static function reorderSegmentAccordingTemplate($arr, $rightOrderedValues, 
        $handleOriginal = null, $handleOrderedValues = null, $addToStart = true)
    {
        $result = [];
        
        $orginalFiltredArray = array_map($handleOriginal, $arr);
        $filtredRightOrderedValues = 
            array_map($handleOrderedValues, $rightOrderedValues);
        
        $orderedPart = [];
        foreach ($filtredRightOrderedValues as $value) {
            if (in_array($value, $orginalFiltredArray)) {
               $currentKey = 
                    ArrElement::getFirstKeyForValue($orginalFiltredArray, $value);
               $orderedPart[$currentKey] = $arr[$currentKey];
               unset($arr[$currentKey]); // убираем из исходного массива, то что "отсортировано"
            }
        }
        
        if ($addToStart) {
            $result = ArrMerger::mergeSavingKeys($orderedPart, $arr);
        } else {
            $result = ArrMerger::mergeSavingKeys($arr, $orderedPart);
        }
        
        return $result; 
    } 
    
    
    /**
     * Произведет сортировку части массива по вложенному в его эелменты значению
     * 
     * @param array $arr  исходный массив
     * @param array $rightOrderedValues  шаблон (в этом массива значения выстроены в нужном порядке)
     * @param \ItForFree\rusphp\PHP\ArrayLib\ArrNestedElement\ArrNestedElement $NestedElement  объект описывающий путь в элементе-массиве
     * @param callable $handleOriginal     обработчик для элемента исходного массив (напр. использующий trim())
     * @param callable  $handleOrderedValues   обработчик для элемента шаблона (напр. использующий trim())
     * @param bool $addToStart           добавлять ли в начало итогового массива (по умолчанию), или в конец (если передано false)
     * @return array
     */
    public static function reorderSegmentByNestedElementAccordingTemplate(
        $arr, $rightOrderedValues, $NestedElement, 
        $handleOriginal = null, $handleOrderedValues = null, $addToStart = true
        )
    {
        $result = [];
        
        $orginalFiltredArray = $NestedElement->arrayMap($handleOriginal, $arr);
        
        $filtredRightOrderedValues = 
            array_map($handleOrderedValues, $rightOrderedValues);
        
        $orderedPart = [];
        foreach ($filtredRightOrderedValues as $value) {
            if (!is_null($currentKey = 
                ArrElement::getFirstKeyForNestedElementValue(
                    $orginalFiltredArray, $value, $NestedElement))) {
                
                $orderedPart[$currentKey] = $arr[$currentKey];
                unset($arr[$currentKey]); // убираем из исходного массива, то что "отсортировано"
            }
        }
        
        if ($addToStart) {
            $result = ArrMerger::mergeSavingKeys($orderedPart, $arr);
        } else {
            $result = ArrMerger::mergeSavingKeys($arr, $orderedPart);
        }
        
        return $result; 
    }
}
