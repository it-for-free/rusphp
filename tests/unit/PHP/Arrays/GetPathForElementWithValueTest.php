<?php

use ItForFree\rusphp\PHP\ArrayLib\Structure as ArrayStructure;
use ItForFree\rusphp\PHP\Object\Exception\CountException;
use ItForFree\rusphp\PHP\Object\Exception\TypeException;
use ItForFree\rusphp\PHP\Object\ObjectFactory;

class TestData {
    
  //данные для первого теста
    
  public static $noHaveAlias = [
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
  
  public static $similarKeysDesiredElementOnTheThirdLevel = [
            'abs' => [
                'abc' => [
                    'abc' => [
                        'alias' => '@asef'
                    ]
                ]
            ]
        ];
  
  //данные для третьего теста
  
  public static $aLotOfNestingDesiredElementOnTheFifthLevel = [
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
  
  public static $desiredElementOnTheThirdLevel = [
            'neq' => 'kklg',
            'arr' => [
                'gdsg' => [
                    'alias' => '@asef'
                ]
            ]
        ];
  
  //данные для пятого теста
  
  public static $desiredElementOnTheSecondLevel = [
            'cvb' => 'bvb',
            'dgsdg' => [],
            'gdfbv' => 'bvcb',
            'fdg' => [
                'alias' => '@asef'
            ]
        ];
  
   //данные для шестого теста
  
  public static $desiredElementInRoot = [
      'class' => 'userTest',
      'key' => 'f02#e4wt',
      'alias' => '@asef',
  ];
  
}


class TestsGetPath extends \Codeception\Test\Unit {


    /*
     * Тест, где нет алиаса
     */
    public function testGetPathNoHaveAlias() {
        
        $ExpectedResponse = false;
        $tester = $this->tester;
        $result = ArrayStructure::getPathForElementWithValue(TestData::$noHaveAlias, 'alias', '@asef');
        $tester->assertSame($ExpectedResponse, $result);
    }
    
    /*
     * Тест с похожими ключами, искомый элемент на третьем уровне
     */
    public function testGetForSimilarKeysDesiredElementOnTheThirdLevel() {
        
        $ExpectedResponse = ['abs', 'abc', 'abc'];
        $tester = $this->tester;
        $result = ArrayStructure::getPathForElementWithValue(TestData::$similarKeysDesiredElementOnTheThirdLevel, 'alias', '@asef');
        $tester->assertSame($ExpectedResponse, $result);
    }
    
    /*
     * Тест с искомым элементом на пятом уровне
     */
    public function testGetForALotOfNestingDesiredElementOnTheFifthLevel() {
        
        $ExpectedResponse = ['tyu', 'mbor', 'gfg', 'gdhb'];
        $tester = $this->tester;
        $result = ArrayStructure::getPathForElementWithValue(TestData::$aLotOfNestingDesiredElementOnTheFifthLevel, 'alias', '@asef');
        $tester->assertSame($ExpectedResponse, $result); //сравнение ожидаемого значения и полученного
    }
    
    /*
     * Тест с искомым элементом на третьем уровне
     */
    public function testGetDesiredElementOnTheThirdLevel() {
        
        $ExpectedResponse = ['arr', 'gdsg'];
        $tester = $this->tester;
        $result = ArrayStructure::getPathForElementWithValue(TestData::$desiredElementOnTheThirdLevel, 'alias', '@asef');
        $tester->assertSame($ExpectedResponse, $result);
    }
    
    /*
     * Тест с искомым элементом на втором уровне
     */
    public function testGetForDesiredElementOnTheSecondLevel() {
        
        $ExpectedResponse = ['fdg'];
        $tester = $this->tester;
        $result = ArrayStructure::getPathForElementWithValue(TestData::$desiredElementOnTheSecondLevel, 'alias', '@asef');
        $tester->assertSame($ExpectedResponse, $result);
    }
    
    /*
     * Тест с искомым элементом на первом уровне
     */
    public function testGetForDesiredElementInRoot() {
        
        $ExpectedResponse = [];
        $tester = $this->tester;
        $result = ArrayStructure::getPathForElementWithValue(TestData::$desiredElementInRoot, 'alias', '@asef');
        $tester->assertSame($ExpectedResponse, $result);
    }
}
