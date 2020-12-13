<?php
use ItForFree\rusphp\PHP\Object\ObjectFactory;

class ObjectTestForParams
{
    public $a;
    public $b;
    public $c;

    public function __construct($a, $b, $c)
    {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }
}

class SetPublicParams extends \Codeception\Test\Unit
{
    public function testObjectParams()
    {
        $obj = ObjectFactory::setPublicParams(new ObjectTestForParams('1', '2', '3'), [
            'd', 'e', 'f'
        ]);
    }
}