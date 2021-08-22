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

class MixedParameterInTheConstructor
{
    public $constructParam;
    
    public function __construct(mixed $variable)
    {
        $this->constructParam = $variable;
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
    
    
//    
    public function testCreateObjectByConstructWithoutTest()
    {
        $tester = $this->tester;
         
        $obj = ObjectFactory::createObjectByConstruct(ObjectForProperty::class, [
            'constructParam' => 77
        ]);
        $secondObject = ObjectFactory::createObjectByConstruct(ObjectTestByClass::class, [
            'oneProperty' => 'line of link'
        ]);

        $tester->assertSame($obj->jump, "I am jumping");
        $tester->assertSame($obj->constructParam, 77);
        $tester->assertSame($secondObject->oneProperty, "line of link");
        $tester->assertSame($secondObject->twoProperty, 2);
    }
    
    public function testCreateObjectWithMixedType()
    {
        $tester = $this->tester;
        
        $objectInt = ObjectFactory::createObjectByConstruct(MixedParameterInTheConstructor::class, [
            'variable' => 5
        ]);
        
        $objectString = ObjectFactory::createObjectByConstruct(MixedParameterInTheConstructor::class, [
            'variable' => 'line'
        ]);
        
        $objectArray = ObjectFactory::createObjectByConstruct(MixedParameterInTheConstructor::class, [
            'variable' => [5]
        ]);
        
        $tester->assertSame($objectInt->constructParam, 5);
        $tester->assertSame($objectString->constructParam, 'line');
        $tester->assertSame($objectArray->constructParam, [5]);
    }
}
