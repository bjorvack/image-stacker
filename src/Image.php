<?php

namespace Bjorvack\ImageStacker;

use Intervention\Image\ImageManagerStatic;

class Image
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     */
    private $x = 0;

    /**
     * @var int
     */
    private $y = 0;

    public function __construct($filePath, $name, $width = null, $height = null)
    {
        $this->filePath = $filePath;
        $this->name = $name;
        $this->width = $width;
        $this->height = $height;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getFilePath()
    {
        return $this->getFilePath();
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param int $y
     *
     * @return self
     */
    public function setY($y)
    {
        $this->y = $y;

        return $this;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param int $x
     *
     * @return self
     */
    public function setX($x)
    {
        $this->x = $x;

        return $this;
    }

    /**
     * Creates an image from a stacker.
     *
     * @param Stacker $stacker
     * @param $filename
     */
    public static function createFromStacker(Stacker $stacker, $filename)
    {
        $image = ImageManagerStatic::canvas($stacker->getSize()['width'], $stacker->getSize()['width']);
        $tile = null;

        foreach ($stacker->getImages() as $image) {
            $image->insert($image->filepath, null, $image->x, $image->y);
        }

        $image->save($filename);
    }
}
