<?php

namespace ItForFree\rusphp\Common\Phone\PhoneNumber;
use ItForFree\rusphp\PHP\Str\StrCommon;


/**
 * Для работы с номером телефонаю
 */
class PhoneNumber 
{
   
    /*
     * @var int|string исходный вид строки с телефоном.
     */
    protected $sourcePhoneText;
    

    /**
     *
     * @var string Код страны (начиная с +) 
     */
    protected $countryCode = '';
    
    /**
     * Номер: только цифры и знак плюса (если таковой имеется)
     * @var string
     */
    protected $clearPhoneNumber  = null;
    
    public function __construct($phoneStr, $countryCode = '+7') 
    {
        $this->sourcePhoneText = $phoneStr;
        $this->countryCode = $countryCode;
    }
    
    /**
     * Вернёт только цифры и знак плюса (если таковой имеется)
     */
    public function getClear()
    {
       $result = $this->clearPhoneNumber;
       if (is_null($result)) {
           $result = preg_replace("/[^0-9+]/", "", $this->sourcePhoneText); 
           $this->clearPhoneNumber = $result;
       }

       return $result;
    }
    
    
}
