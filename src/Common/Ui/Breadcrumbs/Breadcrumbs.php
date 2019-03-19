<?php

namespace ItForFree\rusphp\Common\Ui\Breadcrumbs;

/**
 * Класс дял описания совокупности (списка) хлебных крошек
 */
class Breadcrumbs 
{ 
    /**
     * @var BreadcrumbsElement[] массив элементов-крошек
     */
    protected $parents;
    
    
    /**
     * @var srting название текущего (последнего в списке, самого правого)
     *  элемента (обычно оно не делается ссылкой)
     */
    public $current;
    
    /**
     *  Добавляем очередной элемент
     * 
     * @param string $text Выводимый текст (имя)
     * @param string $url  ссылка (url)
     */
    public function add($text, $url)
    {
       $this->parents[] = new BreadcrumbsElement($text, $url); 
    }
    
    /**
     * Вернёт массив родительских элементов 
     * (название текущего элемента получайте отдельно)
     * 
     * @return BreadcrumbsElement[]  массив родительских элементов
     */
    public function get()
    {
       $parents[] = new BreadcrumbsElement($text, $url); 
    }
}
