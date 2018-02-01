<?php

namespace ItForFree\rusphp\Log;

/**
 * Общий родитель для разных логгеров 
 */
abstract class SimpleLog
{
    /**
     * Активно ли логгирование 
     * (обратите внимание, что эта статическая переменная)
     * 
     * @var boolean 
     */
    protected static $log = true;
    
    
}