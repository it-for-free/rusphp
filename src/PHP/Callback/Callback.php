<?php

namespace ItForFree\rusphp\PHP\Callback;

/**
 * Для работы с функциями обратного вызова типа callable
 */
class Callback {
    
    /**
     * Применит переданную функцию (если она не пуста) к единственному значению
     * 
     * @param callable $callable Функция обратного вызова, которую нужно применить
     * @param mixed $value       Значение к которому надо применить
     */
    public static function do($callable, $value)
    {
        if (!empty($callable))
        {
            $value = $callable($value);
        }
        return $value;
    }
}
