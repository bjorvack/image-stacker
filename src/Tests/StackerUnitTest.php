<?php

namespace Bjorvack\ImageStacker\Tests;

use Bjorvack\ImageStacker\Image;
use Bjorvack\ImageStacker\Stacker;

class StackerUnitTest extends BaseTest
{
    private $fileName;

    private $storagePath;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->fileName = dirname(__FILE__) .'/bjorvack.png';
        $this->storagePath = dirname(__FILE__);
    }

    public function testStackerCreation()
    {
        $stacker = new Stacker('unitTest', 450, 400);

        $this->assertInstanceOf(Stacker::class, $stacker);
        $this->assertEquals(450, $stacker->getMaxWidth());
        $this->assertEquals(400, $stacker->getMaxHeight());
        $this->assertTrue($stacker->canGrowHorizontaly());
        $this->assertTrue($stacker->canGrowVerticaly());
        $this->assertEquals('unitTest', $stacker->getName());

        $stacker = new Stacker('unitTest', null, null, false, false);

        $this->assertInstanceOf(Stacker::class, $stacker);
        $this->assertNull($stacker->getMaxWidth());
        $this->assertNull($stacker->getMaxHeight());
        $this->assertFalse($stacker->canGrowHorizontaly());
        $this->assertFalse($stacker->canGrowVerticaly());
        $this->assertEquals('unitTest', $stacker->getName());

        $this->assertInternalType('array', $stacker->getImages());
        $this->assertEquals(0, count($stacker->getImages()));

        $this->assertInternalType('array', $stacker->getFreeSpaces());
        $this->assertEquals(0, count($stacker->getFreeSpaces()));
    }

    public function testStackerUpdate()
    {
        $stacker = new Stacker('unitTest', 8000, 8000);
        $image = new Image($this->fileName, 'unitTest');
        $stacker->addImage($image);

        $image = new Image($this->fileName, 'unitTest', 40, 20);
        $stacker->addImage($image);

        $image = new Image($this->fileName, 'unitTest', 20, 20);
        $stacker->addImage($image);

        $stacker->stack();

        $this->assertEquals(2, count($stacker->getFreeSpaces()));
        $this->assertEquals(3, count($stacker->getImages()));
    }

    public function testStackerSize()
    {
        $stacker = new Stacker('unitTest', 8000, 8000);
        $image = new Image($this->fileName, 'unitTest');
        $stacker->addImage($image);
        $stacker->stack();

        $this->assertInternalType('array', $stacker->getSize());
        $this->assertEquals(180, $stacker->getSize()['width']);
        $this->assertEquals(180, $stacker->getSize()['height']);
    }

    public function testJsonData()
    {
        $stacker = new Stacker('unitTest', 8000, 8000);
        $image = new Image($this->fileName, 'unitTest');
        $stacker->addImage($image);
        $stacker->stack();
        $json = json_encode($stacker);

        $filename = str_replace('/', '\/', $this->fileName);

        $this->assertEquals('{"name":"unitTest","size":{"width":180,"height":180},"images":[{"name":"unitTest","path":"' . $filename . '","size":{"width":180,"height":180},"position":{"x":0,"y":0}}]}', $json);
    }
}
