<?php

namespace Bjorvack\ImageStacker\Exporters;

use Bjorvack\ImageStacker\Helpers\StringTransformer;
use Bjorvack\ImageStacker\Stacker;

class StylesheetExporter extends Exporter
{
    const NEWLINE = "\n";
    const TAB = "\t";

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

        $filename = rtrim($path, '/') . '/' . StringTransformer::slugify($this->stacker->getName()) . '.css';

        $fp = fopen($filename, 'w');
        fwrite($fp, $this->convertStackToCss($this->stacker, $image->getFilePath()));
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

    /**
     * Converts the stack to a valid css file.
     *
     * @param Stacker $stacker
     * @param $imagePath
     *
     * @return string
     */
    private function convertStackToCss(Stacker $stacker, $imagePath)
    {
        $css = '.' . StringTransformer::slugify($stacker->getName()) . '{' . self::NEWLINE;
        $css .= self::TAB . 'display: block;' . self::NEWLINE;
        $css .= self::TAB . 'background-image: url("' . $imagePath . '");' . self::NEWLINE;
        $css .= '}';

        foreach ($stacker->getImages() as $image) {
            $css .= self::NEWLINE . self::NEWLINE;
            $css .= '.' . StringTransformer::slugify($stacker->getName()) . '-' . StringTransformer::slugify($image->getName()) . '{' . self::NEWLINE;
            $css .= self::TAB . 'width: ' . $image->getWidth() . 'px;' . self::NEWLINE;
            $css .= self::TAB . 'height: ' . $image->getHeight() . 'px;' . self::NEWLINE;
            $css .= self::TAB . 'background-position: ' . $image->getX() . 'px ' . $image->getY() . 'px;' . self::NEWLINE;
            $css .= '}';
        }

        return $css;
    }
}
