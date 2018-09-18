<?php

use ItForFree\rusphp\Common\Phone\PhoneNumber\RussianPhoneNumber;

class RussianPhoneNumberTest extends \Codeception\Test\Unit
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
    
    /**
     *
     * @var array массив массивов
     * исходный номер | чистый вид | tel: вид | является ли внутренним
     */
    protected  $testPhones = [
            ['+7 (945) 345 67 89', '+79453456789', '+79453456789', false],
            ['8 (945), 345-67-89', '89453456789', '+79453456789', false],
            ['(945) 345-67-89', '9453456789', '+79453456789', false],
            ['345-67-89', '3456789', '3456789', false],
            ['5-67-89', '56789', '56789', true],
            ['8 (945)  345-67-89', '89453456789', '+79453456789', false],
            
        ];
    

    public function testClear()
    {  
        $tester = $this->tester;

        $clear = [];
        
        foreach ($this->testPhones as $key => $phone) {
            $currentClear = (new RussianPhoneNumber($phone[0]))->getClear();
            $clear[$key] = $currentClear;
        }
        
        foreach ($this->testPhones as $key => $phone) {
            $tester->assertSame($this->testPhones[$key][1], $clear[$key]);
        }
        
        return $clear;
   
    }
    

    public function testIsInner()
    {  
        foreach ($this->testPhones as $key => $phone) {        
            $this->tester->assertSame($this->testPhones[$key][3], 
                (new RussianPhoneNumber($phone[0]))->isInner());
        }
    }
    
    
    /**
     * @depends testClear
     */
    public function testTelFormat($clearPhones)
    {  
        $tester = $this->tester;

        $telValues = [];
        
        foreach ($clearPhones as $key => $phone) {
            $current = (new RussianPhoneNumber($phone))->getCallValue();
            $telValues[$key] = $current;
        }
        
        foreach ($this->testPhones as $key => $phone) {        
            $tester->assertSame($this->testPhones[$key][2], $telValues[$key]);
        }
   
    }
}