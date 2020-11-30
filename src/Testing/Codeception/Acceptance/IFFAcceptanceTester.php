<?php
namespace ItForFree\rusphp\Testing\Codeception\Acceptance;

use ItForFree\rusphp\Network\Url\Url as Url;

/**
 * Обобщенный класс, добавляющий полезный функционал ACtor- идам тестирования
 * 
 * require: codeception
 */
class IFFAcceptanceTester extends \AcceptanceTester
{

    /**
     * Получит текущий URL  
     * (адрес в рамках сайта -- без доменного имени)
     * 
     * @return type
     */
    public function grabUrl()
    {
        $I = $this;
        
        $url  = $I->grabFromCurrentUrl();
        return $url; 
    }
    
    /**
     * Получит get-параметр из текущего URL
     * 
     * @param string $paramName  имя параметра 
     * @return string            значение параметра
     */
    public function grabUrlParam($paramName)
    {
  
        $urlString  = $this->grabUrl();
        
        $Url = new Url($urlString);
        
        return $Url->getParam($paramName); 
    }

}