<?php

namespace ItForFree\rusphp\Network\Url;

/**
 * ООП представление сущности Url
 * -- в т.ч. для работы с веб интернет ссылками 
 * 
 * Базируется на стандартной функции parse_url():
 *  @link https://secure.php.net/manual/ru/function.parse-url.php
 *
 * @author vedro-compota
 */
class Url {
    
    /**
     * (если есть) Схема (протокол), например: http 
     * @var string 
     */
    public $scheme;
    
    /**
     * Адрес хоста (если есть)
     * @var string 
     */
    public $host;
    
    /**
     * Порт (если есть)
     * @var string 
     */
    public $port;
    
    
    /**
     * Имя пользователя (если есть)
     * @var string 
     */
    public $user;
    
    /**
     * Пароль (если есть)
     * @var string 
     */
    public $pass;
    
    /**
     * Путь (если есть)
     * например: /itforfree/rusphp/network
     * @var string 
     */
    public $path;
    
    /**
     * Строка параметров (если есть)
     * напр. для GET-параметров в http, что начинаются после знака ?, скажем:
     * id=555&id2=777
     * 
     * @var string 
     */
    public $query;
    
    /**
     * (если есть)
     * @var string 
     */
    public $fragment;
    
    /*------------далее дополнительные поля------------*/
    
    /**
     * Ассоцитивный массив GET параметров, построенный на базе $this->query
     * фунцией parse_str() @link https://secure.php.net/manual/ru/function.parse-str.php
     * @var array 
     */
    protected $queryParams;
    
    /**
     * 
     * @param string $url строка, содержащая url (ссылку)
     */
    public function __construct($url) 
    {
        $urlData = parse_url($url);
        
        if ($urlData) {
            foreach ($urlData as $valueName => $value) {
                $this->$valueName = $value;
            }
        } else {
            throw new \Exception("Cant parse url = $url");
        }
        
        parse_str($parts['query'], $this->queryParams);

    }
    
    /**
     * Получить GET параметр по имени
     * 
     * @param type $parameterName
     */
    
    /**
     * Получить  значение GET-параметра по его имени
     * 
     * @param string $parameterName  имя get-параметра
     * @return string
     * @throws \Exception
     */
    public function getParam($parameterName)
    {
        $paramValue = '';
        if (isset($this->$queryParams[$parameterName])) {
            $paramValue = $this->$queryParams[$parameterName]; 
        } else {
            throw new \Exception("There isnt any parameter with name '$parameterName' in queryStr = $this->query");
        }
        
        return $paramValue;
    }
}
