<?php

namespace ItForFree\rusphp\PHP\ArrayLib;

/**
 * Хэши для массива. Хеширование
 * 
 * Основная заметка по вопросу @see http://fkn.ktu10.com/?q=node/10936
 */
class ArrHash
{
    /**
     * Вычислит md5 хэш от даного массива.
     * По поводу выбора вариантов реализации см. тут: @see http://fkn.ktu10.com/?q=node/10936
     * 
     * @param array $arr массив для которого нужно вычислить хэш
     * @param boolean $controlOrder Опционально: нужно ли рекурсивно сортировать массив 
     *   (важно напр. если массивы с одними и теми же элементами, но в разном порядке считаются одинаковыми). По умолчанию опция выключена.
     * @return string
     */
    public static function md5($arr, $controlOrder = false)
    {
        if ($controlOrder) {
            array_multisort($arr);
        }
        
        return md5(json_encode($arr));
    }
}