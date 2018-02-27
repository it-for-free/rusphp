<?php
namespace ItForFree\rusphp\Testing\Codeception\Acceptance;


use ItForFree\rusphp\Network\Url\Url as Url;

/**
 * Обобщенный класс, добавляющий полезный функционал ACtor- идам тестирования
 * 
 * require: codeception
 */
class  IFFAcceptanceTester extends \AcceptanceTester
{
    public function __construct(\Codeception\Scenario $scenario) {
        parent::__construct($scenario);
        
        $this->outputWriter = new \Codeception\Lib\Console\Output([]); 
    }
    
    /**
     * Хранит класс, с помощью которого можно писать в консоль
     * @var \Codeception\Lib\Console\Output
     */
    protected $outputWriter = null; 
    
    /**
     * Специальный маркер для перевод строки один раз за время работы теста
     * 
     * @var boolean 
     */
    protected $newLineAlreadyExists = false;
    
    /**
     * Log additinal info into console
     * Вывод дополнительной технической информации о процессе работы теста в консоль.
     * 
     * Поддерживает тэги symfony/console @link https://symfony.com/doc/current/console/coloring.html
     * 
     * @param type $str
     */
    public function log($str)
    {
        $this->checkNewLineNeed();
        $message ="⚑ $str";
        
        // echo ("$message \n");
        $this->outputWriter->writeln("$message");
    }
    
    /**
     * Вызывается один раз для перевода строки после вывода имени теста
     */
    protected function checkNewLineNeed()
    {
        if (!$this->newLineAlreadyExists) {
            echo "\n";
            $this->newLineAlreadyExists = true;
        }
    }

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