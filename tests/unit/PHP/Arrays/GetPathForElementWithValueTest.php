<?php

use ItForFree\rusphp\PHP\ArrayLib\Structure as ArrayStructure;
use ItForFree\rusphp\PHP\Object\Exception\CountException;
use ItForFree\rusphp\PHP\Object\Exception\TypeException;
use ItForFree\rusphp\PHP\Object\ObjectFactory;

class NumberOne {
    
    public $arrs;
    
    /*
     * Класс теста номер 1
     */
    public function __construct() {
        
        $this->arrs = [
            'one' => 'name',
            'two' => 'lastname',
            'three' => [
                'part' => [
                    'sername' => [
                        'uuu' => 'neoo'
                    ]
                ]
                
            ],
        ];
    }
}

/*
* Класс теста номер 2
*/
class NumberTwo{
    
    public $arrs;
    
    public function __construct() {
        
        $this->arrs = [
            'abs' => [
                'abc' => [
                    'abc' => [
                        'alias' => '@asef'
                    ]
                ]
            ]
        ];
    }
    
}

/*
* Класс теста номер 3
*/
class NumberThree {
    
    public $arrs;
    
    public function __construct() {
        
        $this->arrs = [
            'qwe' => [
                'gfd' => [
                    'dgsdg' => [
                        'fsdgsdg' => 'dfsdg'
                    ]
                ]                
            ],
            'ert' => [
                'fdfsf' => [
                    'gsdgw' => [
                        'dfbvb' => [
                            'gsder' => 'nvc'
                        ]
                    ]                    
                ]
            ],
            'tyu' => [
                'mbor' => [
                    'gfg' => [
                        'gdhb' => [
                            'fdh' => 'gdg',
                            'alias' => '@asef'
                        ]
                    ]
                ]
            ]
        ];
    }
}

/*
* Класс теста номер 4
*/
class NumberFour {
    
    public $arrs;
    
    public function __construct() {
        
        $this->arrs = [
            'neq' => 'kklg',
            'arr' => [
                'gdsg' => [
                    'alias' => '@asef'
                ]
            ]
        ];
    }
}

/*
* Класс теста номер 5
*/
class NumberFive {
    
    public $arrs;
    
    public function __construct() {
        
        $this->arrs = [
            'cvb' => 'bvb',
            'dgsdg' => [],
            'gdfbv' => 'bvcb',
            'fdg' => [
                'alias' => '@asef'
            ]
        ];
    }
    
}


class TestsGetPath extends \Codeception\Test\Unit {


    /*
     * Тест номер 1
     */
    public function testGetPathForElementValueOne() {
        
        $ExpectedResponse = [];
        $tester = $this->tester;
        $object = new NumberOne();
        $arrs = $object->arrs;
        $result = ArrayStructure::getPathForElementWithValue($arrs, 'alias', '@asef');
        $twoResult = $result;
        $tester->assertSame($ExpectedResponse, $result);
    }
    
    /*
     * Тест номер 2
     */
    public function testGetForElementValueTwo() {
        
        $ExpectedResponse = ['abs', 'abc', 'abc'];
        $tester = $this->tester;
        $object = new NumberTwo();
        $arrs = $object->arrs;
        $result = ArrayStructure::getPathForElementWithValue($arrs, 'alias', '@asef');
        $twoResult = $result;
        $tester->assertSame($ExpectedResponse, $result);
    }
    
    /*
     * Тест номер 3
     */
    public function testGetForElementValueThree() {
        
        $ExpectedResponse = ['tyu', 'mbor', 'gfg', 'gdhb'];
        $tester = $this->tester;
        $object = new NumberThree();
        $arrs = $object->arrs;
        $result = ArrayStructure::getPathForElementWithValue($arrs, 'alias', '@asef');
        $twoResult = $result;
        $tester->assertSame($ExpectedResponse, $result); //сравнение ожидаемого значения и полученного
    }
    
    /*
     * Тест номер 4
     */
    public function testGetForElementValueFour() {
        
        $ExpectedResponse = ['arr', 'gdsg'];
        $tester = $this->tester;
        $object = new NumberFour();
        $arrs = $object->arrs;
        $result = ArrayStructure::getPathForElementWithValue($arrs, 'alias', '@asef');
        $twoResult = $result;
        $tester->assertSame($ExpectedResponse, $result);
    }
    
    /*
     * Тест номер 5
     */
    public function testGetForElementValueFive() {
        
        $ExpectedResponse = ['fdg'];
        $tester = $this->tester;
        $object = new NumberFive();
        $arrs = $object->arrs;
        $result = ArrayStructure::getPathForElementWithValue($arrs, 'alias', '@asef');
        $twoResult = $result;
        $tester->assertSame($ExpectedResponse, $result);
    }
}
