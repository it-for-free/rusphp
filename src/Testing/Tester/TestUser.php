<?php

namespace ItForFree\rusphp\Testing\Tester;

/**
 * Description of TestUser
 */
class TestUser
{

    protected $emailBase = '@test.test';
    public $baseName = 'tester';

    /**
     * @var bool добавлять ли строку с текущей датой к поляем, которые могут быть уникальными (типа email и имени)
     */
    protected $addCurrentDateTimeStr = false;
    public $email = null;
    public $username = null;

    /**
     * Из переданного в конструктор baseName будет произведено разделение по тире или нижнему подчеркиванию
     * @var string 
     */
    public $humanName = 'John Smith';
    public $description = 'created by automatic tests code';
    public $password = '12345';

    /**
     * 
     * @param string $baseName         базовое имя, ы качестве разделителя желательно использовать тире
     * @param bool $addCurrentDateTimeStr  добавлять ли строку с текущей датой к уникальным полям (типа email и имени пользователя)
     */
    public function __construct($baseName = '',
        $addCurrentDateTimeStr = false)
    {
        $this->addCurrentDateTimeStr = $addCurrentDateTimeStr;
        if (!empty($baseName)) {
            $this->baseName = $baseName;
        }

        $this->setValues();
    }

    protected function setValues()
    {
        $addTime = $this->addCurrentDateTimeStr;
        $date = new \DateTime();
        $base = $this->baseName;
        $dateTimeStrForMachineNames = $date->format('Y-m-d-H-i-s');
        $dateTimeStrForNames = $date->format('Y-m-d H:i:s');

        $this->username = $base;
        $this->email = $base .
            ($addTime ? '-' . $dateTimeStrForMachineNames : '')
            . $this->emailBase;


        $this->humanName = ucwords(implode(' ', explode('-', $base))
            . ($addTime ? ' ' . $dateTimeStrForNames : ''));
    }

}
