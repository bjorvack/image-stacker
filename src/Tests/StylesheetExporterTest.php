<?php

namespace Bjorvack\ImageStacker\Tests;

use Bjorvack\ImageStacker\Exporters\StylesheetExporter;
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

        $packedImage = $this->storagePath.'/'.$stacker->getName().'.png';
        $packedCSS = $this->storagePath.'/'.$this->slugify($stacker->getName()).'.css';

        $this->assertTrue(file_exists($packedImage), $packedImage . " doesn't exist");
        $this->assertTrue(file_exists($packedCSS), $packedCSS . " doesn't exist");

        $css = '.unittest{display:block;background-image:url("' . $packedImage . '");}';
        $css .= '.unittest-bjorvack{width:180px;height:180px;background-position:0px0px;}';

        $this->assertEquals(
            $this->simplyfyString(file_get_contents($packedCSS)),
            $css
        );

        unlink($packedImage);
        unlink($packedCSS);
    }

    private function simplyfyString($text)
    {
        return str_replace("\r", '', str_replace("\n", '', str_replace("\t", '', str_replace(' ', '', $text))));
    }

    private function slugify($string)
    {
        $string = utf8_encode($string);
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $string = preg_replace('/[^a-z0-9- ]/i', '', $string);
        $string = str_replace(' ', '-', $string);
        $string = trim($string, '-');
        $string = strtolower($string);

        if (empty($string)) {
            return 'n-a';
        }

        return $string;
    }
}
