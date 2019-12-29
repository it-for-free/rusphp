<?php

use ItForFree\rusphp\File\Path;

class FilePathTest extends \Codeception\Test\Unit
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

    public function testPathsConcatination()
    {
        $tester = $this->tester;

        $testValues = [
            [
                ['file/my', 'path/to'],
                'file/my/path/to'
            ],
            [
                ['file/my/', '/path/to'],
                'file/my/path/to'
            ],
            [
                ['file/my/', 'path/to'],
                'file/my/path/to'
            ],
            [
                ['file/my/', 'path/to/my.png'],
                'file/my/path/to/my.png'
            ],
            [
                ['file/my/', 'folder/to/folder', 'path/to/my.png'],
                'file/my/folder/to/folder/path/to/my.png'
            ],
            [
                ['file/my/', '/folder/to/folder', 'path/to/my.png'],
                'file/my/folder/to/folder/path/to/my.png'
            ]
        ];

        foreach ($testValues as $test) {
            $tester->assertSame(Path::concat($test[0], '/'), $test[1]);
        }
    }

}
