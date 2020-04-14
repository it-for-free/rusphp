<?php

use ItForFree\rusphp\Log\Time\Timer;

class TimerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }
    

    public function testStartEndWork()
    {  
        $tester = $this->tester;

        Timer::start('test');
		sleep(1);
		$time = Timer::get('test');
		$tester->assertTrue(Timer::get('test') >= 1);
    }
    


}