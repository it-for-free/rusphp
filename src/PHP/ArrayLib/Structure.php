<?php

namespace ItForFree\rusphp\PHP\ArrayLib;


/**
 * Класс (методы) Изменяет структуру массива или извлекает данные в указанном виде --
 * для работы со сложными вложенными массивами.
 * 
 * (напр. разворачивание в одномерный массив значений многомерного)
 */
class Structure
{
    /**
     * Все значения будут выстроены в линию (одномерный массив)
     * с "заходом" во вложенные массивы
     * (рекурсивная функция)
     *
     * @param array $nestedArray массив, которые может содержать вложенные массивы
     * @return array
     */
    public static function getAllValuesAsOneDemisionalArray($nestedArray)
    {
        $result = array();
        foreach ($nestedArray as $key => $arrElement) {
            if (is_array($arrElement)) {
                $subResults = self::getAllValuesAsOneDemisionalArray($arrElement);
                $result = array_merge($result, $subResults);
            } else {
                $result[] = $arrElement;
            }
        }

        return $result;
    }

    /**
     * Вернёт индекс (ключ) самого длинного вложенного подмассива массива
     *
     * @param array $nestedArray массив, которые может содержать вложенные массивы
     * @param array $allowedKeys ограничивающий список -- если нужно чтобы возвращаемый элементы был исключительно из этого этого списка
     * @return mixed               имя ключа (индекса) или тгдд
     */
    public static function getKeyOfLogestSubarray($nestedArray, $allowedKeys = array())
    {
        $maxLength = 0;
        $arrayKeyName = null;
        foreach ($nestedArray as $key => $value) {
            if (is_array($value) && (empty($allowedKeys) || in_array($key, $allowedKeys))) {
                $length = count($value);
                if ($length >= $maxLength) {
                    $maxLength = $length;
                    $arrayKeyName = $key;
                }
            }
        }

        return $arrayKeyName;
    }

    /**
     * Вернёт массив ключей тех элементов, котроые сами являются массивами
     *
     * @param mixed[] $arr
     * @return mixed[]
     */
    public static function getSubArraysKeys($arr)
    {
        $keys = array();
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $keys[] = $key;
            }
        }
        return $keys;
    }

    /**
     * Извлечет из каждого элемента массива указаные поля первого уровня
     *
     * @param array $arr массив или любая итерируемая структура, к элементам которой можно обращаться как к элементам массива
     * @param string[] $elementsNames имена извлекаемых элементом
     * @return array    двумерный массив, содержащий только запрошенные поля
     */
    public static function getFields($arr, $elementsNames)
    {
        $result = [];
        $i = 0;
        foreach ($arr as $value) {
            foreach ($elementsNames as $name) {
                $result[$i][$name] = $value[$name];
            }
            $i++;
        }
        return $result;
    }

    /**
     *
     * @param array $arr
     * @param string $fieldName
     * @param string $value
     * @return array Находит массив ключей пути до элемента, начиная с корня исходого массива
     * Функция основана на рекурсии: если текущий элемент в цикле является массивом, то функция вызывается рекурсивно,
     *  перед этим проверяется переданный для поиска ключ и значение. Если текущий элемент в цикле содержит переданный для поиска ключ и значение,
     *  то она возвращает ключ текущего элемента массива, таким образом формируется весь путь к псевдониму.
     */
    public static function getPathForElementWithValue($arr, $fieldName, $fieldValue)
    {
        $result = [];
        $found = false;
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                if (isset($value[$fieldName]) && ($value[$fieldName] === $fieldValue)) {
                    return [$key];
                } else {
                    $recResult = self::getPathForElementWithValue($value, $fieldName, $fieldValue);
                    if (!empty($recResult)) {
                        array_unshift($recResult, $key);
                        return ($recResult);
                    }
                }
            }
        }
        return $result;
    }
}