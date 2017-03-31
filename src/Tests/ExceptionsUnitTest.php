<?php

namespace Bjorvack\ImageStacker\Tests;

use Bjorvack\ImageStacker\Exceptions\CantPlaceImageException;
use Bjorvack\ImageStacker\Exceptions\InvalidSizeException;
use Bjorvack\ImageStacker\Exceptions\StackCantGrowException;
use Bjorvack\ImageStacker\FreeSpace;
use Bjorvack\ImageStacker\Image;
use Bjorvack\ImageStacker\Stacker;

class ExceptionsUnitTest extends BaseTest
{
    private $fileName;

    private $storagePath;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->fileName = dirname(__FILE__) .'/bjorvack.png';
        $this->storagePath = dirname(__FILE__);
    }

    /**
     * @expectedException Bjorvack\ImageStacker\Exceptions\CantPlaceImageException
     */
    public function testCantPlaceImageException()
    {
        $freeSpace = new FreeSpace(0, 0, 1, 1);
        $image = new Image($this->fileName, 'testimage');
        $freeSpace->placeImage($image);
    }

    /**
     * @expectedException Bjorvack\ImageStacker\Exceptions\InvalidSizeException
     */
    public function testInvalidSizeException()
    {
        $freeSpace = new FreeSpace(0, 0, 0, 0);
    }

    /**
     * @expectedException Bjorvack\ImageStacker\Exceptions\StackCantGrowException
     */
    public function testStackCantGrowException()
    {
        $stacker = new Stacker('unittest', 10, 10, false, false);
        $image = new Image($this->fileName, 'testimage');
        $stacker->addImage($image);
    }

    public function testExceptions()
    {
        $exception = new CantPlaceImageException();
        $this->assertInstanceOf(CantPlaceImageException::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);

        $exception = new InvalidSizeException();
        $this->assertInstanceOf(InvalidSizeException::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);

        $exception = new StackCantGrowException();
        $this->assertInstanceOf(StackCantGrowException::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);
    }
}
