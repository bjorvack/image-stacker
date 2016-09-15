<?php

namespace Bjorvack\ImageStacker\Tests;

use Bjorvack\ImageStacker\FreeSpace;
use Bjorvack\ImageStacker\Image;
use Bjorvack\ImageStacker\Stacker;

class FreeSpaceUnitTest extends BaseTest
{
    private $fileName;

    private $storagePath;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->fileName = dirname(__FILE__) .'/bjorvack.png';
        $this->storagePath = dirname(__FILE__);
    }

    public function testFreeSpaceCreation()
    {
        $space = new FreeSpace(0, 10, 20, 30);
        $this->assertInstanceOf(FreeSpace::class, $space);
    }

    public function testImageFits()
    {
        $space = new FreeSpace(0, 0, 1, 1);
        $image = new Image($this->fileName, 'unitTest');

        $this->assertFalse($space->imageFits($image));

        $space = new FreeSpace(0, 0, 10, 10);
        $image = new Image($this->fileName, 'unitTest', 10, 10);

        $this->assertTrue($space->imageFits($image));
    }

    public function testImagePlace()
    {
        $space = new FreeSpace(0, 0, 100, 100);
        $image = new Image($this->fileName, 'unitTest', 10, 10);

        $spaces = $space->placeImage($image);
        $this->assertInternalType('array', $spaces);
        $this->assertEquals(2, count($spaces));

        foreach ($spaces as $subspace) {
            $this->assertInstanceOf(FreeSpace::class, $subspace);
        }
    }

    public function testJsonData()
    {
        $space = new FreeSpace(0, 10, 20, 30);
        $json = json_encode($space);
        $this->assertEquals('{"size":{"width":20,"height":30},"position":{"x":0,"y":10}}', $json);
    }
}
