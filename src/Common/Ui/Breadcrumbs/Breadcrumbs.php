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
    public $parents;
    
    
    /**
     * @var srting название текущего (последнего в списке, самого правого)
     *  элемента (обычно оно не делается ссылкой)
     */
    public $current; 
}
