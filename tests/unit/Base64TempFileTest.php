<?php

use Codeception\Test\Unit;
use ItForFree\rusphp\File\Base64\Base64TempFile;

class Base64TempFileTest extends Unit
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

    public function testCreate()
    {
        $tester = $this->tester;
        
        
        $source  = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAMCAgICAgMCAgIDAwMDBAYEBAQEBAgGBgUGCQgKCgkICQkKDA8MCgsOCwkJDRENDg8QEBEQCgwSExIQEw8QEBD/2wBDAQMDAwQDBAgEBAgQCwkLEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBD/wAARCAAeAB0DASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwC/4e1a01jVY9PNnIN6sclvRSe30rK+Kdqmn3mnra7ow8chOGPPIrG1bxFq3w/1owaXFbyGaFJA93afOud33fm4BB/Gu+1Pwzqnj3RNC1gPZJK9ikku9SBudVY7QAcDrX5rlWQ0cJOljEkoNPdO+qffVH3uY55WxUKmFbbkrbPTddjyEzXGP9dJ/wB9GvWLG1LWVux5JiX+VYjfBrxHkkX+nYyf43/+IrtrbTmtreK3kwWiQISOmQMV4HG2MwtSFFYdq6cr2+R63CcMRSnVda+qVr/MxNQ+Kn7Pn2kW+veIdDu763jWGRzpk12BtHQSpCysBz0Yirtt8dPgnBClvaeNbaKKNQiRppl4qqo4AA8nAHtXwjbznA4rSguWAr9Vr5XCvCMHOSUe1v1TPzmljpUpOXKnfvf9Gj7jHxx+D7jjxvCf+3C8/wDjNZ7/ABh+E5Yn/hNYOTn/AI8bv/4zXxwl449acb1/evnMVwDlmMt7Sc9Ozj/8iezQ4rxuHvyRj9z/AMz/2Q==';
        
        $base64File = new Base64TempFile($source);
        $base64File->copyTo(codecept_output_dir() . '/temp.jpg');
        
        
        $tester->assertFileExists(codecept_output_dir() . '/temp.jpg');
        
    }
}