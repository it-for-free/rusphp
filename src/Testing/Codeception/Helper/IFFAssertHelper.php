<?php
namespace ItForFree\rusphp\Testing\Codeception\Helper;


/**
 * Обобщенный класс для assert-проверок
 * 
 * require: codeception
 */
class IFFAssertHelper extends \Codeception\Module
{
    
    /**
     * Прогонит функцию для всех ключей массива и сравнит результат для каждого ключа, со значением, лежащим по данному ключу
     * 
     * @param array $associativeArray    ассоцитивный массив, где в качестве ключа исходное значение, к которому надо применить  $callableSourceHandler() и получить значение по этому ключу
     * @param type $callableSourceHandler  тестируемая функция
     */
    public function assertResultsForKeysAreLikeValues($associativeArray, $callableSourceHandler)
    {
        foreach ($testValues as $source => $needle) {
            $tester->assertSame($callableSourceHandler($source), $needle);
        } 
    }

}