<?php

namespace Bjorvack\ImageStacker\Tests;

use Bjorvack\ImageStacker\Image;

class ImageUnitTest extends BaseTest
{
    public function testImageCreation()
    {
        $image = new Image('./testimage.png', 'testimage');

        $this->assertEquals('testimage', $image->getName());
    }
}
