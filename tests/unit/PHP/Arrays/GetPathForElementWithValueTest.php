<?php

use ItForFree\rusphp\PHP\ArrayLib\Structure as ArrayStructure;
use ItForFree\rusphp\PHP\Object\Exception\CountException;
use ItForFree\rusphp\PHP\Object\Exception\TypeException;
use ItForFree\rusphp\PHP\Object\ObjectFactory;

class TestData {
    
  //данные для первого теста
    
  public static $arrOneTest = [
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
          
  //данные для второго теста
  
  public static $arrTwoTest = [
            'abs' => [
                'abc' => [
                    'abc' => [
                        'alias' => '@asef'
                    ]
                ]
            ]
        ];
  
  //данные для третьего теста
  
  public static $arrThreeTest = [
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
  
  //данные для четвертого теста
  
  public static $arrFourTest = [
            'neq' => 'kklg',
            'arr' => [
                'gdsg' => [
                    'alias' => '@asef'
                ]
            ]
        ];
  
  //данные для пятого теста
  
  public static $arrFiveTest = [
            'cvb' => 'bvb',
            'dgsdg' => [],
            'gdfbv' => 'bvcb',
            'fdg' => [
                'alias' => '@asef'
            ]
        ];
  
  public static $arrSixTest = [
      'class' => 'userTest',
      'key' => 'f02#e4wt',
      'alias' => '@asef',
  ];
  
}


class TestsGetPath extends \Codeception\Test\Unit {


    /*
     * Тест номер 1
     */
    public function testGetPathForElementValueOne() {
        
        $ExpectedResponse = false;
        $tester = $this->tester;
        $result = ArrayStructure::getPathForElementWithValue(TestData::$arrOneTest, 'alias', '@asef');
        $tester->assertSame($ExpectedResponse, $result);
    }
    
    /*
     * Тест номер 2
     */
    public function testGetForElementValueTwo() {
        
        $ExpectedResponse = ['abs', 'abc', 'abc'];
        $tester = $this->tester;
        $result = ArrayStructure::getPathForElementWithValue(TestData::$arrTwoTest, 'alias', '@asef');
        $tester->assertSame($ExpectedResponse, $result);
    }
    
    /*
     * Тест номер 3
     */
    public function testGetForElementValueThree() {
        
        $ExpectedResponse = ['tyu', 'mbor', 'gfg', 'gdhb'];
        $tester = $this->tester;
        $result = ArrayStructure::getPathForElementWithValue(TestData::$arrThreeTest, 'alias', '@asef');
        $tester->assertSame($ExpectedResponse, $result); //сравнение ожидаемого значения и полученного
    }
    
    /*
     * Тест номер 4
     */
    public function testGetForElementValueFour() {
        
        $ExpectedResponse = ['arr', 'gdsg'];
        $tester = $this->tester;
        $result = ArrayStructure::getPathForElementWithValue(TestData::$arrFourTest, 'alias', '@asef');
        $tester->assertSame($ExpectedResponse, $result);
    }
    
    /*
     * Тест номер 5
     */
    public function testGetForElementValueFive() {
        
        $ExpectedResponse = ['fdg'];
        $tester = $this->tester;
        $result = ArrayStructure::getPathForElementWithValue(TestData::$arrFiveTest, 'alias', '@asef');
        $tester->assertSame($ExpectedResponse, $result);
    }
    
    /*
     * Тест номер 6
     */
    public function testGetForElementValueSix() {
        
        $ExpectedResponse = [];
        $tester = $this->tester;
        $result = ArrayStructure::getPathForElementWithValue(TestData::$arrSixTest, 'alias', '@asef');
        $tester->assertSame($ExpectedResponse, $result);
    }
}
