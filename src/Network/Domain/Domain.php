<?php

namespace ItForFree\rusphp\Network\Domain;

use ItForFree\rusphp\Network\Url\Url;

/**
 * Для работы с доменом
 */
class Domain {
    
    /**
     * 
     * @param string $url строка, содержащая url (ссылку)
     */
    public function __construct($url) 
    {
        $Url = new Url($url);

    }
    
    
}
