<?php

namespace ItForFree\rusphp\Network\Url;

use ItForFree\rusphp\PHP\Str\StrCommon;

/**
 * Для работы с адресами социальных сетей
 * и ссылками на аккаунты месседжеров
 */
class SocialNetworkUrl extends Url {
    
    /**
     * Массив соотвествий между  названием соцсети, написанным латинскими буквами
     *  и доменными именами, к ней относящимися
     * Если несколько доменов
     * 
     * @var array 
     */
    public static $domainAndNameСonformity = [
        'twitter' => 'twitter.com',
        'vkontakte' => ['vk.com', 'vkontakte.ru'],
        'facebook' => 'facebook.com',
        'instagram' => 'instagram.com',
        'odnoklassniki' => ['odnoklassniki.ru', 'ok.ru'],
        'telegram' => ['telegram.me', 't.me'],
    ];
    

    /**
     * Вернёт название социальной сети по переданному URL
     * 
     * @return string
     */
    public function getNetworkName()
    {
        foreach (self::$domainAndNameСonformity as $name => $domains) {
            if (StrCommon::isOneFromArrHere($this->host, $domains)) {
                return $name;
            }
        }
        
        return 'noname';
    }
}
