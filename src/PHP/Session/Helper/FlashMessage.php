<?php

namespace ItForFree\rusphp\PHP\Session\Helper;

use ItForFree\rusphp\PHP\Session\Session;
use ItForFree\rusphp\Common\Ui\Message\Message;

/**
 * Простой класс для хранения "одноразовых" сообщений (уведомлений) в сессии
 *  - позволяет передавать их, например, между редиректами. 
 * Например, сообщения вроде "Данные успещно сохранены!"
 */
class FlashMessage
{
    /**
     * @var string ключ подмассива в $_SESSION по которому будут хранится данные этого класса
     */
    protected static $sectionName = '_session_flash_messages_storage';
    
    public static function set($key, $text, $title = '', $type = '')
    {
        Session::start();
        $Message = new Message($text, $title, $type);
        $_SESSION[self::$sectionName][$key] = $Message;
    }
    
    /**
     * Вернет сообщение и удалит его из хранилища 
     * @param string $key
     * @return \ItForFree\rusphp\Common\Ui\Message\Message|null
     */
    public function get($key)
    {
        Session::start();
        $message = null;
        if (isset($_SESSION[self::$sectionName][$key])) {
            $message = $_SESSION[self::$sectionName][$key];
            unset($_SESSION[self::$sectionName][$key]);
        }
        return $message;
    }
    
    /**
     * Вернет все сообщения и очистит хранилище
     * 
     * @return \ItForFree\rusphp\Common\Ui\Message\Message[] сообщения-уведомления (или пустой массив, если ничего нет)
     */
    public function getAll()
    {
        Session::start();
        $messages = array();
        if (isset($_SESSION[self::$sectionName])) {
            $messages = $_SESSION[self::$sectionName];
            unset($_SESSION[self::$sectionName]);
        }
        return $messages;
    }
}

