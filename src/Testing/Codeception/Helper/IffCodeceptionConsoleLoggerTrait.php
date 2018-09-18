<?php
namespace ItForFree\rusphp\Testing\Codeception\Helper;

/**
 * (этот трейт) Добавляет удобную возможность вывода в консоль
 * данных для различных классов codeception
 * 
 * require: codeception
 */
trait IffCodeceptionConsoleLoggerTrait
{
    /**
     * Хранит класс, непосредственно занимающийся печать.
     * @var \Codeception\Lib\Console\Output 
     */
    protected $outputWriter = null; 
    
    protected function outputWriter() {

        if (!$this->outputWriter) { // если ещё не инициллизировано
            $this->outputWriter = new \Codeception\Lib\Console\Output([]); 
        }
        
       return $this->outputWriter;
    }
    
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
        $this->outputWriter()->writeln("$message");
    }
    
    /**
     * Обёртка над print_r() для тестировщика $I
     * 
     * @param type $value
     * @param string $comment
     */
    public function pre($value, $comment = '')
    {
        $this->checkNewLineNeed();
        
        if ($comment) {
            $comment .= ':';
        }
        $message ="🐛 $comment <debug>" . print_r($value, true) . "</debug>";
        $this->outputWriter()->writeln("$message");
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
}
