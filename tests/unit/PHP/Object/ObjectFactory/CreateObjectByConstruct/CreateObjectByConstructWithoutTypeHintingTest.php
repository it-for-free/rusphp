<?php

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
    
    public function __construct() {
        
        $this->jump = 'I am jumping';
    }
}

class ObjectTestByClass {
    
    public $oneProperty;
    public $twoProperty;
    public $threeProperty;
    
    public function __construct(ObjectForProperty $oneProperty, $twoProperty = 2, $threeProperty = 3)
    {
        $this->oneProperty = $oneProperty;
        $this->twoProperty = $twoProperty;
        $this->threeProperty = $threeProperty;
    }
}


class CreateObjectByConstructWithoutTypeHintingTest {
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
         
        $obj = ObjectFactory::createObjectByConstruct(ObjectTestByConstruct::class, [
            'oneProperty' => new ObjectForProperty,
            'twoProperty' => 50,
            'threeProperty' => 'a boat'
        ]);

        $tester->assertSame($obj->oneProperty instanceof ObjectForProperty, true);
        $tester->assertSame(gettype($obj->twoProperty), 'integer');
        $tester->assertSame(gettype($obj->threeProperty), 'string');
    }
}
