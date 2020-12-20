<?php
use ItForFree\rusphp\PHP\Object\ObjectFactory;

class ObjectDependency1
{
}

class ObjectDependency2
{
}

class integration
{

}


class ObjectTestByConstruct
{
    public $dep;
    public $b;
    public $c;
    
    public function __construct(ObjectDependency1 $dep, int $b = 2, int $c = 3)
    {
        $this->dep = $dep;
        $this->b = $b;
        $this->c = $c;
    }
}

class CreateObjectByConstruct extends \Codeception\Test\Unit
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

    public function testBySimpleArray()
    {
        $tester = $this->tester;
         
        $obj = ObjectFactory::createObjectByConstruct(ObjectTestByConstruct::class, [
            new ObjectDependency1,
            2,
            3
        ]);
        
        $tester->assertSame($obj->dep instanceof ObjectDependency1, true);
        $tester->assertSame(gettype($obj->b), 'integer');
        $tester->assertSame(gettype($obj->c), 'integer');
    }
    
    public function testByFullAssocArrayInDirectOrder()
    {
        $tester = $this->tester;
         
        $obj = ObjectFactory::createObjectByConstruct(ObjectTestByConstruct::class, [
            'dep' => new ObjectDependency1,
            'b' => 50,
            'c' => 100
        ]);

        $tester->assertSame($obj->dep instanceof ObjectDependency1, true);
        $tester->assertSame(gettype($obj->b), 'integer');
        $tester->assertSame(gettype($obj->c), 'integer');
        $tester->assertSame($obj->b ,50);
    }
    
    public function testByFullAssocArrayInNotDirectOrder()
    {
        $tester = $this->tester;
        $data = [
            'b' => 100,
            'c' => new integration,
            'dep' => new ObjectDependency1
        ];


        $obj = ObjectFactory::createObjectByConstruct(ObjectTestByConstruct::class, $data);

        $tester->log(print_r($obj, true));
        
        $tester->assertSame($obj->dep instanceof ObjectDependency1, true);
        $tester->assertSame(gettype($obj->b), 'integer');
        $tester->assertSame(gettype($obj->c), 'integer');
        $tester->assertFalse($obj->b instanceof ObjectDependency1, true);
    }
    
    public function testByNotFullAssocArray()
    {;
        $tester = $this->tester;
         
        $obj = ObjectFactory::createObjectByConstruct(ObjectTestByConstruct::class, [
            'dep' => new ObjectDependency1,
            'c' => 50
        ]);
        
        $tester->assertSame(gettype($obj->b), 'integer');
    }
}