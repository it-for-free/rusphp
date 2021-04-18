<?php

use ItForFree\rusphp\PHP\ArrayLib\Structure as ArrayStructure;
use ItForFree\rusphp\PHP\Object\Exception\CountException;
use ItForFree\rusphp\PHP\Object\Exception\TypeException;
use ItForFree\rusphp\PHP\Object\ObjectFactory;

class NumberOne {
    
}

class TestsGetPath extends \Codeception\Test\Unit
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
     * @var array
     */
    public $arrs = [
        1123,
        'qwe' => [
            'alias' => '@asef',
            'qwe' => 123,
        ]
    ];

    public function testGetPathForElementValue()
    {
        $tester = $this->tester;
        $arrs = $this->arrs;

//        $obj = ObjectFactory::createObjectByConstruct(ArrayStructure::class);
        
        try {
            $result = ArrayStructure::getPathForElementWithValue($arrs, 'alias', '@session');
            $twoResult = $result;
        } catch (Exception $exception) {
            $error = $exception;
//            $tester->assertSame($exception instanceof TypeException, true);
        }
    }
}
