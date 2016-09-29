<?php

namespace Bjorvack\ImageStacker\Tests;

use Bjorvack\ImageStacker\Exporters\JsonExporter;
use Bjorvack\ImageStacker\Helpers\StringTransformer;
use Bjorvack\ImageStacker\Image;
use Bjorvack\ImageStacker\Stacker;

class JsonExporterTest extends BaseTest
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

        JsonExporter::save($stacker, $this->storagePath);

        $packedImage = $this->storagePath.'/'.StringTransformer::slugify($stacker->getName()).'.png';
        $packedJson = $this->storagePath.'/'.StringTransformer::slugify($stacker->getName()).'.json';

        $this->assertTrue(file_exists($packedImage), $packedImage . " doesn't exist");
        $this->assertTrue(file_exists($packedJson), $packedJson . " doesn't exist");

        $this->assertEquals(
            file_get_contents($packedJson),
            '{"name":"unitTest","size":{"width":180,"height":180},"images":[{"name":"bjorvack","path":"' . $filename = str_replace('/', '\/', $this->fileName) . '","size":{"width":180,"height":180},"position":{"x":0,"y":0}}]}'
        );

        unlink($packedImage);
        unlink($packedJson);
    }
}
