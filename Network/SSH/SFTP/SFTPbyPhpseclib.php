<?php

namespace ItForFree\rusphp\Network\SSH\SFTP;

use ItForFree\rusphp\PHP\Str\StrCommon as Str;
use ItForFree\rusphp\Log\SimpleEchoLog as log;

use ItForFree\phpseclib\Net\SFTP as SFTP; // реализация в phpseclib


/**
 *  SFTP -- обёрта библиотеки  phpseclib (psl)
 * phpseclib 2.x @see https://github.com/phpseclib/phpseclib/tree/master 
 */
class SFTPbyPhpseclib extends \phpseclib\Net\SFTP
{
    
    /**
     * Выводить ли ошибки. ВНИМАНИЕ: для безопасности после отладки установите значение false;
     * @var bool 
     */
    public $debug = true;

    /**
     * Переопределяем конструктор
     */
    function __construct() {
        //parent::__construct($host, $port, $timeout);
        
        $this->config();
    }
    
    function config()
    {

    }
    
    /**
     * Сразу подключаеся и авторизируемся
     * 
     * @param string $username  имя пользователя
     * @param string $password  пароль
     * @param string $host      ардрес формата exmaple.com или 127.0.0.1
     * @param int    $port      номер порта, 22 по умолчанию
     * @param type   $timeout   таймаут соединения
     */
    public function connectAndAuthByPass($username, $password, $host, $port = 22, $timeout = 10)
    {
       $this->connect($host, $port, $timeout);
       $this->authByPass($username, $password);  
    }
    
    /**
     * Сразу подключаеся и авторизируемся
     * 
     */
    public function connectAndAuthByPassByArray($options)
    {
        extract($options);
        $this->connectAndAuthByPass($username, $password, $host, $port, $timeout);
    }



    /**
     * Установит соединение с сервером
     * 
     * @param sttring $host
     * @param int $port
     * @param int $timeout
     */
    public function connect($host, $port = 22, $timeout = 30)
    {
        parent::__construct($host, $port, $timeout);
    }
    
    
    /**
     * Авторзиация по логину и паролю
     * 
     * @param string $username  имя пользователя
     * @param string $password  пароль
     * @throws \Exception
     */
    public function authByPass($username, $password)
    {
        if (!$this->login($username, $password)) {
            $description = '';
            if ($this->debug) {
                $description =  $this->allErrorsAndLogs();
            }
            
            throw new \Exception("Login failed! $description ");
        }
        
    }   
    
    /**
     * Простой тест phpseclib -- проверка соединения и авторизации по SSH
     * 
     * @param string $username  имя пользователя
     * @param string $password  пароль
     * @param string $host      ардрес формата exmaple.com или 127.0.0.1
     * @param int    $port      номер порта, 22 по умолчанию
     * @param type   $timeout   таймаут соединения
     */
    public function test($username, $password, $host, $port = 22, $timeout = 50)
    {            
        $this->connectAndAuthByPass($username, $password, $host, $port, $timeout);
        
        log::me('--------- phpseclib handler test --------');
        log::pre($this->exec('pwd'), 'Folder adress');
        log::pre($this->exec('ls -la'), 'Files in it');
        
        
    }
    
    
    /**
     * Скачает файл по SSH SFTP
     * 
     * @param type $remoteFile
     * @param type $localFile
     */
    public function download($remoteFile, $localFile)
    {
        $result = $this->get($remoteFile, $localFile);
        
        if (!$result) {
            throw new \Exception("Error!  Cant download file from $remoteFile to $localFile. Reason: " . $this->allErrorsAndLogs());
        }
        
        return $result;
    }
    
    
    public function allErrorsAndLogs()
    {
       $message = 'Something wen wrong! But error output blocked by library options! ';
       
       if ($this->debug) {
            $message = log::pre($this->getSFTPErrors(), 'Errors', true)
                . log::pre($this->getSFTPLog(), 'Logs', true);
       }
       
       return $message;
    }
    
     
    

}