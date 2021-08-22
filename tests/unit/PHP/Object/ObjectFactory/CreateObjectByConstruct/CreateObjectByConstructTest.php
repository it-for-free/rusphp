<?php

use ItForFree\rusphp\PHP\Object\Exception\CountException;
use ItForFree\rusphp\PHP\Object\Exception\TypeException;
use ItForFree\rusphp\PHP\Object\ObjectFactory;

require __DIR__ . '/includes/constructonClassForCrObByConstTest.php';

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
            'c' => 50,
            'dep' => new ObjectDependency1
        ];


        $obj = ObjectFactory::createObjectByConstruct(ObjectTestByConstruct::class, $data);
        $tester->assertSame($obj->dep instanceof ObjectDependency1, true);
        $tester->assertSame(gettype($obj->b), 'integer');
        $tester->assertSame(gettype($obj->c), 'integer');
        $tester->assertFalse($obj->b instanceof ObjectDependency1, true);
    }
    
    
    public function testByNotFullAssocArray()
    {
        $tester = $this->tester;
         
        $obj = ObjectFactory::createObjectByConstruct(ObjectTestByConstruct::class, [
            'dep' => new ObjectDependency1,
            'c' => 50
        ]);
        
        $tester->assertSame(gettype($obj->b), 'integer');
    }
    

    public function testByWrongParamsTypes()
    {
        $tester = $this->tester;
        try {
            $obj = ObjectFactory::createObjectByConstruct(ObjectTestByConstruct2::class, [
                'dep' => new ObjectDependency1,
                'b' => 'qwe', // неверный тип данных для b и c, что вызывает ошибку в пятом тесте
                'c' => 'rty'  //
            ]);
        } catch (Exception $exception) {

            $tester->assertSame($exception instanceof TypeException, true);
        }
    }
    

    public function testByWrongParamsTypesNotAssocArray()
    {
        $tester = $this->tester;
        try {
            $obj = ObjectFactory::createObjectByConstruct(ObjectTestByConstruct2::class, [
                'dep' => new ObjectDependency1,
                'qwe',
                'rty'  //Вызывает ошибку для шестого теста
            ]);
        } catch (Exception $exception) {

            $tester->assertSame($exception instanceof TypeException, true);
        }
    }
    

    public function testByWrongParamsTypesAssocArray()
    {
        $tester = $this->tester;
        try {
            $obj = ObjectFactory::createObjectByConstruct(ObjectTestByConstruct3::class, [
                'dep' => new ObjectDependency1,
                'b' => 'qwe', //вызывает ошибку для 7 теста
                'c' => 'rty'  //
            ]);
        } catch (Exception $exception) {

            $tester->assertSame($exception instanceof TypeException, true);
        }
    }
    

    public function testByWrongParamsCount()
    {
        $tester = $this->tester;
        try {
            $obj = ObjectFactory::createObjectByConstruct(ObjectTestByConstruct2::class, [
                'dep' => new ObjectDependency1,
                                                //ожидает значения для свойств b и c типа integer, но их нет, поэтому выдает ошибку
            ]);
        } catch (Exception $exception) {

            $tester->assertSame($exception instanceof CountException, true);
        }
    }
    
    
    public function testWrongParamsTypesForDefaults()
    {  
        $tester = $this->tester;
        try {
            $obj = ObjectFactory::createObjectByConstruct(ObjectTestByConstruct::class, [
                'dep' => new ObjectDependency1,
                'b' => 'advc', 
                'c' => 'etc' 
            ]);
        } catch (Exception $exception) {
            
            $tester->assertSame($exception instanceof TypeException, true);
        }
    }
}