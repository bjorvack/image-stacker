<?php

namespace Bjorvack\ImageStacker\Tests;

use Bjorvack\ImageStacker\Helpers\FileManager;
use Bjorvack\ImageStacker\Helpers\StringTransformer;

class HelpersTest extends BaseTest
{
    private $storagePath;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->storagePath = dirname(__FILE__);
    }

    public function testFileManager()
    {
        $this->assertFalse(file_exists($this->storagePath.'/test.txt'));

        FileManager::save(
            'test',
            $this->storagePath.'/test.txt'
        );

        $this->assertTrue(file_exists($this->storagePath.'/test.txt'));
        unlink($this->storagePath.'/test.txt');
    }

    public function testSlugify()
    {
        $this->assertEquals(
            'test-unittest',
            StringTransformer::slugify('test Unittest')
        );
    }

    public function testRemoveWhiteSpace()
    {
        $this->assertEquals(
            'testingtestingtesting',
            StringTransformer::removeWhiteSpace(" testing \n testing \r testing \t")
        );
    }
}
