<?php 

use ItForFree\rusphp\PHP\Str\StrCommon;

class StringCommonTest extends \Codeception\Test\Unit
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

    // tests
    public function testIsEndWith()
    {
        $tester = $this->tester;

        $testValues = [
            'fasdfgadfg.js.map' => 
                ['substr' => '.map',
                'result' =>  true],
            'fasdfgadfg.js.map' => 
                ['substr' => 'fsdzgzdfgzdfgzdfhhgdfbdfbdfb',
                'result' =>  false],
            'fasdfgadfg.js.map' => 
                ['substr' => 'fasdfgadfg.js.map',
                'result' =>  true],
        ];

        foreach ($testValues as $source => $value) {        
            $tester->assertSame(StrCommon::isEndWith(
                $source, $value['substr']), $value['result']);
        }
    }
}