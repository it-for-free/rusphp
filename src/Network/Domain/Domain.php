<?php

namespace ItForFree\rusphp\Network\Domain;

use ItForFree\rusphp\Network\Url\Url;
use ItForFree\rusphp\PHP\ArrayLib\Reverce;

/**
 * Для работы со строкой домена сайта ("с доменом сайта").
 * 
 * Берет сам домен или из переданного в конструктор URL 
 * или из $_SERVER['HTTP_HOST'] (учтите, что этот может быть подделан клиентом)
 */
class Domain {
    
    /**
     * Полное имя домена (без протокола), например
     * subsub.sub2.example.com
     * 
     * @var string 
     */
    public $name;
    
    /** 
     * Берет сам домен или из переданного в конструктор URL 
     * или из $_SERVER['HTTP_HOST'] (учтите, что этот может быть подделан клиентом
     * 
     * @param string $url строка, содержащая url (ссылку)
     */
    public function __construct($url = '') 
    {
        if ($url) {
            $Url = new Url($url);
            $this->name = $Url->host;
        } else {
            $this->name = filter_input(INPUT_SERVER, 'HTTP_HOST');
        }

    }
    
    /**
     * Получит поддомен нужного уровня, минимальный уровень -- 1
     * Например для  subsub.sub2.example.com поддомены/уровни это:
     * 4: subsub
     * 3: sub2
     * 2: example
     * 1: com
     * 
     * @param int $level минимальный уровень =1
     * @return string
     * @throws Exception
     */
    public function getSubdomain($level = 1)
    {
        $subdomains = explode('.', $this->name);
        
        $subdomain = Reverce::getElement($subdomains, $level);
        
        return $subdomain;  
    }
    
    
}
