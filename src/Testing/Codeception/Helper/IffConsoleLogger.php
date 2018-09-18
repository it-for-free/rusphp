<?php
namespace ItForFree\rusphp\Testing\Codeception\Helper;

/**
 * Добавляет удобные методы для логгированния в консоль
 * 
 * codeseption echo log into console
 * 
 * require: codeception
 */
class IffConsoleLogger extends \Codeception\Module
{
    use IffCodeceptionConsoleLoggerTrait;
}
