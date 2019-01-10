<?php 
use ItForFree\rusphp\PHP\Str\Validator;

class StringValidatorTest extends \Codeception\Test\Unit
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
    public function testHumanNameRegexpMatch()
    {
        $tester = $this->tester;
        $testValues = [
            'sdfsd sfdf ' => true,
            ' Примеров-незванов Peter ' => true,
            'Примеров-незванов Peter' => true,
            'dfgfr.dfg' => false,
            'http://dfgfr.dfg' => false
        ];

        foreach ($testValues as $source => $needle) {        
//            $tester->log($source);
//            $tester->pre(Validator::isHumanName($source));
            $tester->assertSame(Validator::isHumanName($source), $needle);
        }
    }
}