<?php 
use ItForFree\rusphp\Common\Time\RequestsTimeInterval;

class RequestsTimeIntervalTest extends \Codeception\Test\Unit
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
    public function testIntervals()
    {
        $tester = $this->tester;
        $testValues = [
            ['result' => true],
            ['result' => true],
            ['result' => true],
            ['result' => true],
            ['result' => false],
            ['result' => false],
            ['result' => false],
            ['result' => false],
            ['result' => true],
            ['result' => true],
            ['result' => false],
            ['result' => true],
            ['result' => true],
        ];
        
        $TimeInteraval = new RequestsTimeInterval(1, 60, 1, 60);

        foreach ($testValues as $test) {        
            
//            $tester->pre(Validator::isHumanName($source));
            $TimeInteraval->update($test['result']);
//            $tester->log('Результат ' . $test['result'] . ' новый интеревал ожидания: ' . $TimeInteraval->getCurrentInterval());
//            $tester->assertSame(Validator::isHumanName($source), $needle);
        }
    }
}