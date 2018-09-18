<?php

namespace ItForFree\rusphp\Common\Phone\PhoneNumber;
use ItForFree\rusphp\PHP\Str\StrCommon;


/**
 * Для работы с номером телефона:
 * - внутренний ли это номер
 * - даёт возможность получить номер в чистом виде (как мы набираем в телефоне, без скобок, пробелов и тире)
 */
class PhoneNumber {
   
    /*
     * @var int|string исходный вид строки с телефоном.
     */
    protected $sourcePhoneText;
    

    
    /**
     * Номер: только цифры и знак плюса (если таковой имеется)
     * @var string
     */
    protected $clearPhoneNumber  = null;
    
    public function __construct($phoneStr, $countryCode = '+7') 
    {
        $this->sourcePhoneText = $phoneStr;
    }
    
    /**
     * Вернёт только цифры и знак плюса (если таковой имеется)
     */
    public function getClear()
    {
       $result = $this->clearPhoneNumber;
       if (is_null($result)) {
           $result = preg_replace("/[^0-9, +]/", "", $this->sourcePhoneText); 
           $this->clearPhoneNumber = $result;
       }

       return $result;
    }
    
    
    /**
     * Проверит является ли номер локальным (если начинается с данной последовательности) и 
     * не начинается с плюса
     * 
     * @param string $localPhoneNumberStart с чего начинается локальный номер (по умолчанию = 8)
     * @return bool
     */
    public function isLocal($localPhoneNumberStart = '8')
    {
        $clear = $this->getClear();
        return (StrCommon::isStart($clear, $localPhoneNumberStart) &&
               !StrCommon::isStart($clear, '+'));
    }
    
    /**
     * Проверит является ли номер внутренним
     * 
     * @param string $innerMaxLength
     * @return bool
     */
    public function isInner($innerMaxLength = 5)
    {
        $clear = $this->getClear();
        return (strlen($clear) >= $innerMaxLength);
    }
}
