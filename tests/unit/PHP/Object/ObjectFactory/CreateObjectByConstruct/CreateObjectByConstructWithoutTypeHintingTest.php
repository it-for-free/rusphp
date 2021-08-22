<?php

use ItForFree\rusphp\PHP\Object\Exception\CountException;
use ItForFree\rusphp\PHP\Object\Exception\TypeException;
use ItForFree\rusphp\PHP\Object\ObjectFactory;

require __DIR__ . '/includes/constructonClassForCrObByConstTest.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CreateObjectByConstructWithoutTypeHintingTest
 *
 * @author qwe
 */
class ObjectForProperty {
    
    public $jump;
    public $constructParam;
    
    public function __construct($constructParam) {
        
        $this->jump = 'I am jumping';
        $this->constructParam = $constructParam;
    }
}

class ObjectTestByClass {
    
    public $oneProperty;
    public $twoProperty;
    public $threeProperty;
    
    public function __construct(string $oneProperty, int $twoProperty = 2, array $threeProperty = [])
    {
        $this->oneProperty = $oneProperty;
        $this->twoProperty = $twoProperty;
        $this->threeProperty = $threeProperty;
    }
}


class CreateObjectByConstructWithoutTypeHintingTest extends \Codeception\Test\Unit
{
    //put your code here
    
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }
    
    
    
    public function testCreateObjectByConstructWithoutTest()
    {
        $tester = $this->tester;
         
        $obj = ObjectFactory::createObjectByConstruct(ObjectForProperty::class, [
            'constructParam' => 77
        ]);
//        $secondObject = ObjectFactory::createObjectByConstruct(ObjectTestByClass::class, [
//            'oneProperty' => 'line of link'
//        ]);

        $tester->assertSame($obj->jump, "I am jumping");
        $tester->assertSame($obj->constructParam, 77);
    }
}
