<?php

namespace Bjorvack\ImageStacker\Exporters;

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

        $filename = rtrim($path, '/') . '/' . $this->stacker->getName() . '.json';

        $fp = fopen($filename, 'w');
        fwrite($fp, json_encode($this->stacker));
        fclose($fp);

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
