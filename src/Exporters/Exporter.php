<?php

namespace Bjorvack\ImageStacker\Exporters;

use Bjorvack\ImageStacker\Image;
use Bjorvack\ImageStacker\Stacker;

abstract class Exporter
{
    /**
     * @var Stacker
     */
    protected $stacker;

    /**
     * Exporter constructor.
     *
     * @param Stacker $stacker
     */
    protected function __construct(Stacker $stacker)
    {
        $this->stacker = $stacker;
    }

    /**
     * Writes the stacker to a file.
     * Returns an array of files.
     *
     * @param string $path
     *
     * @return array
     */
    abstract public function writeToFile($path);

    /**
     * Builds the image from the stacker.
     *
     * @param string $path
     *
     * @return Image
     */
    protected function createImage($path)
    {
        return Image::createFromStacker($this->stacker, $path);
    }
}
