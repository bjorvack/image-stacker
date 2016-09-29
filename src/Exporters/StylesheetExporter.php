<?php

namespace Bjorvack\ImageStacker\Exporters;

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

        $filename = rtrim($path, '/') . '/' . $this->slugify($this->stacker->getName()) . '.css';

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
        $css = '.' . $this->slugify($stacker->getName()) . '{' . self::NEWLINE;
        $css .= self::TAB . 'display: block;' . self::NEWLINE;
        $css .= self::TAB . 'background-image: url("' . $imagePath . '");' . self::NEWLINE;
        $css .= '}';

        foreach ($stacker->getImages() as $image) {
            $css .= self::NEWLINE . self::NEWLINE;
            $css .= '.' . $this->slugify($stacker->getName()) . '-' . $this->slugify($image->getName()) . '{' . self::NEWLINE;
            $css .= self::TAB . 'width: ' . $image->getWidth() . 'px;' . self::NEWLINE;
            $css .= self::TAB . 'height: ' . $image->getHeight() . 'px;' . self::NEWLINE;
            $css .= self::TAB . 'background-position: ' . $image->getX() . 'px ' . $image->getY() . 'px;' . self::NEWLINE;
            $css .= '}';
        }

        return $css;
    }

    /**
     * Transforms the string to a valid slug.
     *
     * @param $string
     *
     * @return string
     */
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
