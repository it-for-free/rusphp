<?php

use Codeception\Test\Unit;
use ItForFree\rusphp\PHP\DateTime\DateTimeFormat;

class DateTimeFormatTest extends Unit
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

    public function testDateFormatChanges()
    {
        $tester = $this->tester;
        
        
        $testValues = [
        
            [
                'source' => '2019-12-03 03:12',
                'result' =>'03.12.2019 03:12',
                'from_format' => 'Y-m-d H:i', // 'Y-m-d H:i:s',
                'to_format'   => 'd.m.Y H:i',   
            ],
            [
                'source' => '2019-12-03 03:12:00',
                'result' =>'2019-12-03 03:12:00', // то же самое, так как строка исходная строка не соответствует формату
                'from_format' => 'Y-m-d H:i', // 'Y-m-d H:i:s',
                'to_format'   => 'd.m.Y H:i',   
            ]
            
        ];

        foreach ($testValues as $key => $test) {
            $tester->assertSame(
                DateTimeFormat::get($test['source'], $test['from_format'], $test['to_format'], true),
                $test['result']
            );
        }
    }
}