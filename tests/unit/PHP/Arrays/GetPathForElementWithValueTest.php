<?php

use ItForFree\rusphp\PHP\ArrayLib\Structure as ArrayStructure;
use ItForFree\rusphp\PHP\Object\Exception\CountException;
use ItForFree\rusphp\PHP\Object\Exception\TypeException;
use ItForFree\rusphp\PHP\Object\ObjectFactory;

class NumberOne {
    
    public $arrs;
    
    public function __construct() {
        
        $this->arrs = [
            'one' => 'name',
            'two' => 'lastname',
            'three' => [
                'part' => [
                    'sername' => [
//                        'alias' => '@asef'
                    ]
                ]
                
            ],
        ];
    }
}

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

class NumberFive {
    
    public $arrs;
    
    public function __construct() {
        
        $this->arrs = [
            'cvb' => 'bvb',
            'dgsdg' => [],
            'gdfbv' => 'bvcb',
            'fdg' => [
//                'alias' => '@asef'
            ]
        ];
    }
    
}


class TestsGetPath extends \Codeception\Test\Unit {

    public $arrs = [
        1123,
        'qwe' => [
            'alias' => '@asef',
            'qwe' => 123,
        ]
    ];

    public function testGetPathForElementValueOne() {
        
        $tester = $this->tester;
        $object = new NumberOne();
        $arrs = $object->arrs;

        try {
            $result = ArrayStructure::getPathForElementWithValue($arrs, 'alias', '@asef');
            $twoResult = $result;
        } catch (Exception $exception) {
            $error = $exception;
//            $tester->assertSame($exception instanceof TypeException, true);
        }
    }
    
    
    public function testGetForElementValueTwo() {
        $tester = $this->tester;
        $object = new NumberTwo();
        $arrs = $object->arrs;

        try {
            $result = ArrayStructure::getPathForElementWithValue($arrs, 'alias', '@asef');
            $twoResult = $result;
        } catch (Exception $exception) {
            $error = $exception;
//            $tester->assertSame($exception instanceof TypeException, true);
        }
    }
    
    public function testGetForElementValueThree() {
        
        $tester = $this->tester;
        $object = new NumberThree();
        $arrs = $object->arrs;
        try {
            $result = ArrayStructure::getPathForElementWithValue($arrs, 'alias', '@asef');
            $twoResult = $result;
        } catch (Exception $exception) {
            $error = $exception;
//            $tester->assertSame($exception instanceof TypeException, true);
        }
    }
    
    public function testGetForElementValueFour() {
        
        $tester = $this->tester;
        $object = new NumberFour();
        $arrs = $object->arrs;

        try {
            $result = ArrayStructure::getPathForElementWithValue($arrs, 'alias', '@asef');
            $twoResult = $result;
        } catch (Exception $exception) {
            $error = $exception;
//            $tester->assertSame($exception instanceof TypeException, true);
        }
    }
    
    public function testGetForElementValueFive() {
        
        $tester = $this->tester;
        $object = new NumberFive();
        $arrs = $object->arrs;

        try {
            $result = ArrayStructure::getPathForElementWithValue($arrs, 'alias', '@asef');
            $twoResult = $result;
        } catch (Exception $exception) {
            $error = $exception;
//            $tester->assertSame($exception instanceof TypeException, true);
        }
    }

}
