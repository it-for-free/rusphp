<?php
use ItForFree\rusphp\PHP\Object\ObjectFactory;

class ObjectDependency1
{
}

class ObjectDependency2
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
        ObjectFactory::createObjectByConstruct(ObjectTestByConstruct::class, [
            new ObjectDependency1,
            2,
            3
        ]);
    }
    
    public function testByFullAssocArrayInDirectOrder()
    {
        ObjectFactory::createObjectByConstruct(ObjectTestByConstruct::class, [
            'dep' => new ObjectDependency1,
            'b' => 2,
            'c' => 3
        ]);
    }
    
    public function testByFullAssocArrayInNotDirectOrder()
    {
        ObjectFactory::createObjectByConstruct(ObjectTestByConstruct::class, [
            'b' => 2,
            'c' => 3,
            'dep' => new ObjectDependency1
        ]);
    }
    
    public function testByNotFullAssocArray()
    {;
        $tester = $this->tester;
         
        $obj = ObjectFactory::createObjectByConstruct(ObjectTestByConstruct::class, [
            'dep' => new ObjectDependency1,
            'c' => 2
        ]);
    }
}