<?php

namespace ItForFree\rusphp\Common\Phone\PhoneNumber;
use ItForFree\rusphp\PHP\Str\StrCommon;


/**
 * Для работы с номером телефона:
 * - внутренний ли это номер
 * - даёт возможность получить номер в чистом виде (как мы набираем в телефоне, без скобок, пробелов и тире)
 */
class RussianPhoneNumber extends PhoneNumber  {
   
    /**
     * Проверит является ли номер локальным --
     * в частности, не начинается с плюса
     * 
     * @param  int $maxLocalLength максимальная длина местного номера в формате без кода региона
     * @return bool
     */
    public function isLocal($maxLocalLengthWithoutRegionCode = 7)
    {
        $clear = $this->getClear();
        return (!StrCommon::isStart($clear, '+') 
            && (strlen($clear) <= $maxLocalLengthWithoutRegionCode))
            || $this->isLocalWithRegionCode()
            || $this->isLocalWithRegionCodeAnd8();
    }
    
    /**
     * Проверит является ли номер локальным --
     * не начинается с плюса, имеет заданную длину
     * 
     * @param int $maxLocalLength  длина номера с кодом региона без кода страны и без восьмёрки 
     * @return type
     */
    public function isLocalWithRegionCode($fullLocalLength = 10)
    {
        $clear = $this->getClear(); 
        return (!StrCommon::isStart($clear, '+') 
            && ((strlen($clear) == $fullLocalLength) 
            || ((strlen($clear) == ($fullLocalLength + 1)) && StrCommon::isStart($clear, '8')))
        );
    }
    
    /**
     * Проверит является ли номер локальным --
     * не начинается с плюса, имеет заданную длину и начинается с восьмёрки
     * 
     * @param int $maxLocalLength длина номера с кодом региона без кода страны и без восьмёрки
     * @return 
     */
    public function isLocalWithRegionCodeAnd8($fullLocalLength = 10)
    {
        $clear = $this->getClear(); 
        return (!StrCommon::isStart($clear, '+') 
            && ((strlen($clear) == ($fullLocalLength + 1)) && StrCommon::isStart($clear, '8'))
        );
    }

    /**
     * Проверит является ли номер условно внутренним
     * (условный метод, на деле отличить для произвольного региона России внутренний номер от произвольного 
     * регионального без кода региона нельзя, т.к. встречаются напр. как 5тизначные внутренние номера внутри организаций так и аналогичные номера внутри регионов)
     * 
     * @param string $innerMaxLength
     * @return bool
     */
    public function isInner($innerMaxLength = 5)
    {
        $clear = $this->getClear();
        return (strlen($clear) <= $innerMaxLength);
    }
    
    /**
     * Получит номер в чистом виде для звонка (если возможно, то в международно формате)
     * Можно использовать для ссылок tel:
     * 
     * @return string
     */
    public function getCallValue()
    {
        $clear = $this->getClear();
        $callable = null;
        if ($this->isLocalWithRegionCodeAnd8()) {
            $callable = substr_replace($clear, $this->countryCode, 0, 1);
        } elseif ($this->isLocalWithRegionCode()) {
            $callable = $this->countryCode . $clear;
        } else {
            $callable = $clear; // оставляем без преобразований, если это номер уже в международном формате, локальный/внутренний или вообще неясного типа
        }
            
        return $callable;
    }
}
