<?php

/**
 * Безопасность, секретные ключи
 */

namespace ItForFree\rusphp\PHP\Security;

/**
 * Для работы с токенами вида "секретнаястрока_таймстемп"
 * Тайстемп (временная метка), как подразумевается, хранит дату создания токена
 *
 */
class TokenWithTimestamp {

    /**
     * Проверит устарел ли ключ (если значение пусто, то считаем, что устарел)
     * 
     * @param string $tokenWithTimestamp   токен и тайсмстэмп с временем создания токена, соединенные нижним подчеркиванием
     * @param int  $periodInSeconds     время жизни ключа в секундах (моментом создания считается прикреплённая временная метка)
     * @return boolean                  закончился ли указанный период
     */
    public static function IsPeriodEnded($tokenWithTimestamp, $periodInSeconds) 
    {
        if (empty($tokenWithTimestamp)) {
            return true;
        }

        $timestamp =  substr($tokenWithTimestamp, strrpos($tokenWithTimestamp, '_') + 1);

        vdie($timestamp);
        return $timestamp + $periodInSeconds >= time();
    }

}
