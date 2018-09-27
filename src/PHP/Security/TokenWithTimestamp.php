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
     * Проверит устарел ли ключ
     * 
     * @param string $token_timestamp   токен и тайсмстэмп с временем создания токена, соединенные нижним подчеркиванием
     * @param int  $periodInSeconds     время жизни ключа в секундах (моментом создания считается прикреплённая временная метка)
     * @return boolean                  закончился ли указанный период
     */
    public static function IsPeriodEnded($token_timestamp, $periodInSeconds) {
        if (empty($token_timestamp)) {
            return true;
        }

        $timestamp = (int) substr($token_timestamp, strrpos($token, '_') + 1);

        return $timestamp + $periodInSeconds >= time();
    }

}
