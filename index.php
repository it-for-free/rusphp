<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '/var/www/iff/rusphp/src/PHP/Object/ObjectClass/Constructor.php';
require '/var/www/iff/rusphp/src/PHP/Object/ObjectFactory.php';

use ItForFree\rusphp\PHP\Object\ObjectClass\Constructor;
use ItForFree\rusphp\PHP\Object\ObjectFactory;

function debug($var = []) {
    echo '<pre>';
    print_r($var);
    echo '</pre>';
    die;
}

class Before
{
}

class Testing
{
    public $before;
    
    public function __construct(Before $before)
    {
        $this->before = $before;
    }
}

//$objF = ObjectFactory::createObjectByConstruct('Testing', [
//    'before' => new Before
//]);


$data = [
    'b' => 100,
    'c' => 50,
    'dep' => new ObjectDependency1
];


$obj = ObjectFactory::createObjectByConstruct(ObjectTestByConstruct::class, $data);