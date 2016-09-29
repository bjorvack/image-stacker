<?php

namespace Bjorvack\ImageStacker\Tests;

use Bjorvack\ImageStacker\Exporters\StylesheetExporter;
use Bjorvack\ImageStacker\Helpers\StringTransformer;
use Bjorvack\ImageStacker\Image;
use Bjorvack\ImageStacker\Stacker;

class StylesheetExporterTest extends BaseTest
{
    private $fileName;

    private $storagePath;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->fileName = dirname(__FILE__) .'/bjorvack.png';
        $this->storagePath = dirname(__FILE__);
    }

    public function testCreateFromStacker()
    {
        $stacker = new Stacker('unitTest');
        $image = new Image($this->fileName, 'bjorvack');
        $stacker->addImage($image);
        $stacker->stack();

        StylesheetExporter::save($stacker, $this->storagePath);

        $packedImage = $this->storagePath.'/'.StringTransformer::slugify($stacker->getName()).'.png';
        $packedCSS = $this->storagePath.'/'. StringTransformer::slugify($stacker->getName()) .'.css';

        $this->assertTrue(file_exists($packedImage), $packedImage . " doesn't exist");
        $this->assertTrue(file_exists($packedCSS), $packedCSS . " doesn't exist");

        $css = '.unittest{display:block;background-image:url("' . $packedImage . '");}';
        $css .= '.unittest-bjorvack{width:180px;height:180px;background-position:0px0px;}';

        $this->assertEquals(
            StringTransformer::removeWhiteSpace(file_get_contents($packedCSS)),
            $css
        );

        unlink($packedImage);
        unlink($packedCSS);
    }
}
