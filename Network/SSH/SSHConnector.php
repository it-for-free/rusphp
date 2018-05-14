<?php

namespace ItForFree\rusphp\Network\SSH;

use ItForFree\rusphp\PHP\Str\StrCommon as Str;
use ItForFree\rusphp\Log\SimpleEchoLog as log;

/**
 * Базовый класс для организации соединения,
 *  с реализация на примере возможностей расширения libssh2
 * Для установления соединения через SSH
 */
class SSHConnector
{
    protected $host;
    protected $port;
    protected $username;
    protected $password;
    
    /**
     * Обработчики событий соединения/подключения
     * @var array 
     */
    private $connectionCallbacks = array(
        'disconnect' => 'SSHConnector::disconnectHandler',
    );
    
    /**
     * Настройки и предпочтения подлюкчения
     * @var array 
     */
    private $connectionMethods = array(
        'kex' => 'diffie-hellman-group1-sha1',
        'client_to_server' => array(
          'crypt' => '3des-cbc',
          'comp' => 'none'),
        'server_to_client' => array(
          'crypt' => 'aes256-cbc,aes192-cbc,aes128-cbc',
          'comp' => 'none')
     );
    
    
    /**
     * Единая функция с упрощённой (минимальный необхоходимый набор параметров) 
     * формой подключения и авторизации по логину и паролю/
     * 
     * @param string $host      адрес хоста
     * @param int    $port      номер порта
     * @param string $username  имя ползователя
     * @param string $password  пароль
     * @return resource         дескриптор соединения (нужен для последующей работы с удалённым сервером)
     * @throws \Exception
     */
    public function simpleConnectAndAuthByPass($host, $port, $username, $password)
    {
        $connection = $this->connect($host, $port);   
        $auth = ssh2_auth_password($connection, $username, $password);
        
        if (!$auth) {
            throw new \Exception("Cannot auth with this pass and username!");
        }
        
        return $connection;  
    }
    
    
    /**
     * Делает попытку подключиться к серверу по SSH
     * (на основе стандартной функции ssh2_connect)
     * см. @see http://php.net/manual/ru/function.ssh2-connect.php
     * 
     * @param string $host          адрес хоста
     * @param string $port          номер порта
     * @param array $methods        настройки и предпочтения подлюкчения
     * @param array $callbacks      обработчики событий соединения/подключения
     * @return resource             дескриптор соединения
     * @throws \Exception 
     */
    public function connect($host, $port, $methods = false, $callbacks = false)
    {
        
        $methods = $methods ?: $this->connectionMethods;
        $callbacks = $callbacks ?: $this->connectionCallbacks;
        
        $connection = \ssh2_connect($host, $port, $methods, $callbacks);
        if (!$connection) {
            throw new \Exception("Cannot connect to server $host:$port !");
        }
        
        return $connection;
    }
    
    /**
     * Функция оповещения о разрыве соединения. Сигнатура стандартная
     * см. @see http://php.net/manual/ru/function.ssh2-connect.php
     */
    private static function disconnectHandler($reason, $message, $language) {
        log::me("Server disconnected with reason code [$reason] and message: $message");
    }  
}