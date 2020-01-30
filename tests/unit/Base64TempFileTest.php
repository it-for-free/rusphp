<?php

use Codeception\Test\Unit;
use ItForFree\rusphp\File\Base64\Base64TempFile;
use ItForFree\rusphp\File\Directory\Directory;
use ItForFree\rusphp\File\Path;
use ItForFree\rusphp\File\MIME;

class Base64TempFileTest extends Unit
{

    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $testOutputDir = '';

    
    protected $testStrFromJs = "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wgARCAAyADIDAREAAhEBAxEB/8QAHAAAAgIDAQEAAAAAAAAAAAAAAAEGBwQFCAID/8QAFgEBAQEAAAAAAAAAAAAAAAAAAAEC/9oADAMBAAIQAxAAAAHqbIAB0CgADV1CalxvYUMryqirGOpD7Cisyma35aRM4zYxK5rqckzJpCgHXqo7G9j0AAOlAAAf/8QAHhAAAgMAAgMBAAAAAAAAAAAAAwQCBQYBEAAUMCD/2gAIAQEAAQUC+VjYirV+NkuA1dAkmO9BrFa5J+xDfU2cWbeOCUZD60GilGtq2EDKiSGBlTOGYSUW9QPjLHII7ZRlQ9Ln6mtpcujAKH5YpQslhDgcfl//xAAUEQEAAAAAAAAAAAAAAAAAAABQ/9oACAEDAQE/AUf/xAAUEQEAAAAAAAAAAAAAAAAAAABQ/9oACAECAQE/AUf/xAAxEAACAQIDBAgFBQAAAAAAAAABAgMEEQASIRMiMYEFEBRBQlFxkSAwMmGxIzNyofH/2gAIAQEABj8C+U0sh4C+XE20lE6llyCPQx30yt+efpirmkN8zZF9F/34KoQVERq4xormw98RmqaqKQzCOuV77WIH6BkHG+mv288UzVlO/R1FCpR4AZAankTujX+z54GUZQPDa1uuslo5uyrRuRNUSrpp4R6nTFDUQ5qivedtopVTK0g1F7t+3lPvxwazsscnSTrsYoabMfQC/kLXbQWHvT9umMM0e8i0zHcvxUk3zYCZ2kPe7cT1XETyfxF8S1cUdTPAXLk7DSIkAOuv1KwPvzxE8FzUFCmdlIcg8RblywtUx2tXMP1JD9vCvcF8rfFmeSXZls5hzbt/yORwFUBVAsAO75f/xAAgEAEBAQACAgIDAQAAAAAAAAABESEAMSBBUWEQcYHR/9oACAEBAAE/IfCeVcXUOp8/r764KpyItnYlJq34exPIZGaafVX+vhuCxbtiZl7/ALyvYsKeAQNrLJNclc9Og9ZOVvaStHK/kCvR6nLyc7pVh1ycVe9M36tx3CNVUQUdZQrETq7h0mXtuMb0DxQQ0E+aEHLgZnD9qctfnM/AatZ6n875i8+hQxQ4xw6POwyOY6uJH9kfs4+iDMhSY4Wxj3tvk/RlEv5jJLFAFOtaF8wcAdAePry//9oADAMBAAIAAwAAABDbYDbYADLCCBASbAQbAJbbDbbf/8QAFBEBAAAAAAAAAAAAAAAAAAAAUP/aAAgBAwEBPxBH/8QAGREAAgMBAAAAAAAAAAAAAAAAEBEBIDBB/9oACAECAQE/EMkUVkxGLty3/8QAHRABAQACAgMBAAAAAAAAAAAAAREAITFBECBRYf/aAAgBAQABPxD0E4kfUFwSWFq7cAGrTtDeGsgjRN2koUphEYpnbsJiU7dHkehyPgLknBYqYqAEcwgfmX3eG9CQdGkot3RCMC0RipUBwE5U2OaEfAgnGvmsnBOVvAWyGhQTZKjTiMKTvKOGgynjAFdeFhYcSsCpMLUn9DCj5g4qjRV3JzqRBQQaAgddu/A+IE8y8oahvRP0z4ehwa6jnF8SsWiyEW6QDBsEaFBElqgiBuQDS01vkY4NMiLxaBvQAumFOIPKnTUA0AAAcevb2//Z";
    
    protected function _before()
    {
        $path = codecept_output_dir() . '/Base64TempFileTest';
        Directory::createRecIfNotExists($path);

        $this->testOutputDir = $path;
    }

    protected function _after()
    {
        Directory::clear($this->testOutputDir, true);
    }

    public function testCreateFromString()
    {
        $tester = $this->tester;

        

        $base64File = new Base64TempFile($this->testStrFromJs);
        $filePath = $this->testOutputDir . '/temp.' . $base64File->getExtention();
        $base64File->copyTo($filePath);

        $tester->assertFileExists($filePath);
    }

    public function testFilesExtentionDetermination()
    {
        $testSourceFolder = codecept_data_dir() . '/images';
        $filesPaths = Directory::getAllFilesPaths($testSourceFolder);

        $number = 0;
        foreach ($filesPaths as $path) {
             $number++;
             
             $dataUrlBase64 = Base64TempFile::convertToDataUrl($path); 
             
             $base64File = new Base64TempFile($dataUrlBase64);      
             $newFilePath = $this->testOutputDir . "/$number." . $base64File->getExtention();
             $base64File->copyTo($newFilePath);
             
             $this->tester->assertSame(MIME::getForFile($path), MIME::getForFile($newFilePath)); // можно было бы сравнить Path::getExtention(), но не пойдет так как jpg переводится в jpeg
        }
    }

}
