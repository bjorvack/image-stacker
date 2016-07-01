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

    /**
     * Image constructor.
     *
     * @param string $filePath
     * @param string $name
     * @param int|null $width
     * @param int|null $height
     */
    public function __construct($filePath, $name, $width = null, $height = null)
    {
        $this->filePath = $filePath;
        $this->name = $name;
        $this->width = $width;
        $this->height = $height;

        if ($height === null || $width === null) {
            $this->setDimensions();
        }
    }

    /**
     * Get the image dimensions from the file.
     */
    private function setDimensions()
    {
        list($width, $height) = getimagesize($this->filePath);

        if ($this->height === null) {
            $this->height = $height;
        }

        if ($this->width === null) {
            $this->width = $width;
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @return int|null
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int|null
     */
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
     * @param string $storagePath
     *
     * @return Image
     */
    public static function createFromStacker(Stacker $stacker, $storagePath)
    {
        $imageCanvas = ImageManagerStatic::canvas($stacker->getSize()['width'], $stacker->getSize()['width']);

        foreach ($stacker->getImages() as $image) {
            $imageCanvas->insert($image->filePath, null, $image->x, $image->y);
        }

        $filename = $storagePath.'/'.$stacker->getName().'.png';
        
        $imageCanvas->save($filename);

        return new self(
            $filename,
            $stacker->getName(),
            $stacker->getSize()['width'],
            $stacker->getSize()['height']
        );
    }
}
