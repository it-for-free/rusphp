<?php

namespace ItForFree\rusphp\Common\Ui\Message;

/**
 * Базовый класс для описания сообщения/уведомления
 */
class Message 
{ 
    /**
     * @var string тип сообщения
     */
    public $type;
    
    /**
     * @var string заголовок сообщения
     */
    public $title;
    
    /**
     * @var string текст (тело сообщения)
     */
    public $text;
    
    /**
     * 
     * @param string $text  текст
     * @param string $title необязательный заголовок
     * @param string $type необязательный тип
     */
    public function __construct($text, $title, $type) 
    {
        $this->text = $text;
        $this->title = $title;
        $this->type = $type;
    }
}
