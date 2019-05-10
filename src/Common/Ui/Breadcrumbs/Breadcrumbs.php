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
       return $this->parents; 
    }
    
    /**
     * Выведет html для имеющихся хлебных крошек
     * 
     * @param type $start  начало блока
     * @param type $end  конец блока
     * @param type $startElement  начало обычно элмента
     * @param type $endElement конец обычного элемента
     * @param type $delimiter  разделитель
     * @param type $printCurrent   выводить ли текущий (последний) элемент
     * @param type $currentStart начало текущего элемента (все что кроме самой ссылки)
     * @param type $currentEnd  конец текущего элемента (все что кроме самой ссылки)
     */
    public function printHtml(
            $start = '<ul>',
            $end = '</ul>',
            $startElement = '<li>',
            $endElement = '</li>',
            $delimiter = ' | ',
            $printCurrent = true,
            $currentStart = '<strong>',
            $currentEnd = '</strong>')
    {

        echo $start;
        if (!empty($this->parents)) {
            $total = count($this->parents);
            $count = 0;
            foreach ($this->get() as $parent) {
                $count++;
                echo $startElement . "<a href='" 
                    . Path::addEndSlash($parent->url, '/') . "'> "
                    . $parent->text ." </a>" . $endElement;
                if ($count != $total) {
                    echo $delimiter ;
                }
            }
        }
        if ($printCurrent) {
            echo $delimiter . $currentStart . $this->current . $currentEnd;
        }
        echo $end;
    }
}
