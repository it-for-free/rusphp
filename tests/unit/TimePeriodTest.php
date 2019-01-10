<?php

use Codeception\Test\Unit;
use ItForFree\rusphp\Common\Time\TimePeriod;
//use UnitTester;

class TimePeriodTest extends Unit
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

    public function testChangeTermFormatToStrict()
    {
        $tester = $this->tester;
        $testValues = [
            '12' => [
                    'years' => 1,
                    'months' => 0,
                ],
            '3' => [
                    'years' => 0,
                    'months' => 3,
                ],
            '18' => [
                    'years' => 1,
                    'months' => 6,
                ],
            '45' => [
                    'years' => 3,
                    'months' => 9,
                ],
            '0' => [
                    'years' => 0,
                    'months' => 0,
                ],
        ];

        foreach ($testValues as $source => $needle) {
            $tester->assertSame(TimePeriod::changeTermFormatToStrict($source), $needle);
        }
    }

    public function testTermToString()
    {
        $tester = $this->tester;
        $testValues = [
            'Не указано' => [0, 0],
            '1 год 1 месяц' => [1, 1],
            '2 года 2 месяца' => [2, 2],
            '5 лет 5 месяцев' => [5, 5],
            '13 лет 7 месяцев' => [13, 7],
        ];

        foreach ($testValues as $needle => $source) {
            $tester->assertSame(TimePeriod::termToString($source[0], $source[1]), $needle);
        }

        $tester->expectThrowable(
            new LogicException('Количество месяцев не может быть больше 11. Увеличьте количество лет.'),
            function () {
                TimePeriod::termToString(1, 15);
            }
        );
    }
}