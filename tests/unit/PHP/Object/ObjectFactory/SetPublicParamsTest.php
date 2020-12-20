<?php
use ItForFree\rusphp\PHP\Object\ObjectFactory;

/**
 * Class ObjectTestForParams
 */
class ObjectTestForParams
{
    public $a;
    public $b;
    public $c;

    public function __construct(string $a, string $b, string $c)
    {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }
}

class SetPublicParams extends \Codeception\Test\Unit
{
    /**
     * Тест для сеттера
     */
    public function testObjectParams()
    {
        $data = [
            'd' => 'd',
            'e' => 'e',
            'f' => 'f'
        ];
        $obj = ObjectFactory::setPublicParams(new ObjectTestForParams('1', '2', '3'), $data);

    }
}