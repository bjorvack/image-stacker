<?php

namespace Bjorvack\ImageStacker\Exporters;

use Bjorvack\ImageStacker\Helpers\FileManager;
use Bjorvack\ImageStacker\Helpers\StringTransformer;
use Bjorvack\ImageStacker\Stacker;

class JsonExporter extends Exporter
{
    /**
     * Exports the stacker to a json file and an image.
     *
     * @param $path
     *
     * @return array
     */
    public function writeToFile($path)
    {
        $image = $this->createImage($path);

        $filename = rtrim($path, '/') . '/' . StringTransformer::slugify($this->stacker->getName()) . '.json';

        FileManager::save(
            json_encode($this->stacker),
            $filename
        );

        return [
            'file' => $filename,
            'image' => $image->getFilePath(),
        ];
    }

    /**
     * Static constructor.
     *
     * @param Stacker $stacker
     *
     * @return JsonExporter
     */
    public static function make(Stacker $stacker)
    {
        return new self($stacker);
    }

    /**
     * Saves the stacker to a file and saves the image.
     *
     * @param Stacker $stacker
     * @param $path
     *
     * @return array
     */
    public static function save(Stacker $stacker, $path)
    {
        $exporter = self::make($stacker);

        return $exporter->writeToFile($path);
    }
}
