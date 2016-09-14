<?php

namespace Bjorvack\ImageStacker\Tests;

use Bjorvack\ImageStacker\Image;
use Bjorvack\ImageStacker\Stacker;

class ImageUnitTest extends BaseTest
{
    private $fileName;

    private $storagePath;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->fileName = dirname(__FILE__) .'/bjorvack.png';
        $this->storagePath = dirname(__FILE__);
    }

    public function testImageCreation()
    {
        $image = new Image($this->fileName, 'testimage');

        $this->assertEquals('testimage', $image->getName());
        $this->assertEquals($this->fileName, $image->getFilePath());

        $image = new Image($this->fileName, 'testimage', 200, 300);

        $this->assertEquals(200, $image->getWidth());
        $this->assertEquals(300, $image->getHeight());
        $this->assertEquals(0, $image->getX());
        $this->assertEquals(0, $image->getY());
    }

    public function testPositionChanges()
    {
        $image = new Image('./testimage.png', 'testimage', 200, 300);

        $image->setX(200);
        $image->setY(300);

        $this->assertEquals(200, $image->getX());
        $this->assertEquals(300, $image->getY());
    }

    public function testCreateFromStacker()
    {
        $stacker = new Stacker('unitTest');
        $image = new Image($this->fileName, 'bjorvack');
        $stacker->addImage($image);
        $stacker->stack();

        $packedImage = $this->storagePath.'/'.$stacker->getName().'.png';

        $this->assertNotTrue(file_exists($packedImage));
        $image = Image::createFromStacker($stacker, $this->storagePath);

        $this->assertEquals(get_class($image), Image::class);
        $this->assertTrue(file_exists($packedImage));

        unlink($packedImage);
    }
}
