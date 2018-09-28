<?php

/**
 * Безопасность, секретные ключи
 */

namespace ItForFree\rusphp\PHP\Security;

use ItForFree\rusphp\PHP\Str\StrCommon;

/**
 * Для работы с токенами вида "секретнаястрока_таймстемп"
 * Тайстемп (временная метка), как подразумевается, хранит дату создания токена
 *
 */
class TokenWithTimestamp
{
    /**
     * @var string  токен и тайсмстэмп с временем создания токена, соединенные нижним подчеркиванием (устанавливается в конструкторе)
     */
    protected $sourceToken = '';

    protected $token = '';
    
    protected $timestamp = null;
      
    /**
     * 
     * @param string $sourceToken токен и тайсмстэмп с временем создания токена, соединенные нижним подчеркиванием
     */
    public function __construct($sourceToken) 
    {
        if (!empty($sourceToken)) {
            $this->sourceToken = $sourceToken;
            $this->timestamp =  substr($sourceToken, strrpos($sourceToken, '_') + 1);
            $this->token = StrCommon::replaceSubStrInTheEnd($sourceToken, '_' . $this->timestamp);
        }
    }
            
    /**
     * Проверит устарел ли ключ (токен) (если значение пусто, то считаем, что устарел)
     * 
     * @param int  $periodInSeconds     время жизни ключа в секундах (моментом создания считается прикреплённая временная метка)
     * @return boolean                  закончился ли указанный период
     */
    public function isPeriodEnded($periodInSeconds) 
    {
        $tokenWithTimestamp = $this->sourceToken;
        if (empty($tokenWithTimestamp)) {
            return true;
        }

        $timestamp = $this->timestamp;

        return $timestamp + $periodInSeconds < time();
    }
    
    /**
     * Обновит временную метку
     * 
     * @param int $newTimestamp  новыая временная метка
     * @return $this
     */
    public function updateTimestamp($newTimestamp = null)
    {
        $this->timestamp = $newTimestamp ?: time();
        return $this;
    }
    
    /**
     * Вернёт актуальную строку вида токен_временнаяМетка
     */
    public function getFullStr()
    {
        if  (!empty($this->token) && !empty($this->timestamp))
        { 
            return $this->token . '_' . $this->timestamp;
        } else {
            return '';
        }
    }
    
    /**
     * 
     * @return string Токен БЕЗ временной метки
     */
    public function getToken()
    {
        return $this->token;
    }
    
    /**
     * 
     * @return stringвременная метка
     */
    public function getTimestamp()
    {
        return $this->token;
    }
}
