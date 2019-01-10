<?php 

use ItForFree\rusphp\PHP\Str\StrFilter;

class StringFiltersTest extends \Codeception\Test\Unit
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
    public function testFilterTagsAndProtocols()
    {
        $tester = $this->tester;

        $testValues = [
            'dfgfr.fg http://dfgdfg.tu <br> ert' => 'dfgfr.fg dfgdfg.tu  ert',
            'dfgfr.<b>fg</b> <br> ftp://ert sdf https://gfr3.toy' => 'dfgfr.fg  ert sdf gfr3.toy'
        ];

        foreach ($testValues as $source => $needle) {        
            $tester->assertSame(StrFilter::tagsAndProtocols($source), $needle);
        }
    }
}