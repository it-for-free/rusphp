<?php

namespace ItForFree\rusphp\Common\Ui\Breadcrumbs;

/**
 * Элемент "хлебных крошек"
 */
class BreadcrumbsElement 
{ 
    /**
     * @var srting Выводимый текст (имя) 
     */
    public $text;
    
    
    /**
     * @var srting ссылка (url)
     */
    public $url; 
    
    /**
     * Элемент "хлебных крошек"
     * 
     * @param string $text Выводимый текст (имя)
     * @param string $url  ссылка (url)
     */
    public function __construct($text, $url = '') {
        $this->text = $text;
        $this->url = $url;
    }
}
