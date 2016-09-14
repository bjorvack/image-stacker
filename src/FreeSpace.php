<?php

namespace Bjorvack\ImageStacker;

use Bjorvack\ImageStacker\Exceptions\CantPlaceImageException;
use Bjorvack\ImageStacker\Exceptions\InvalidSizeException;

class FreeSpace implements \JsonSerializable
{
    /**
     * @var string
     */
    private $x;

    /**
     * @var string
     */
    private $y;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    public function __construct($x, $y, $width = null, $height = null)
    {
        if ($width <= 0 || $height <= 0) {
            throw new InvalidSizeException('A free space must have a with and height');
        }

        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Checks if an image fits in a free space.
     *
     * @param Image $image
     *
     * @return bool
     */
    public function imageFits(Image $image)
    {
        if ($image->getHeight() > $this->height || $image->getWidth() > $this->width) {
            return;
        }

        return true;
    }

    public function placeImage(Image $image)
    {
        if (!$this->imageFits($image)) {
            throw new CantPlaceImageException('The image doesn\'t fit in the free space.');
        }

        $image->setX($this->x);
        $image->setY($this->y);

        $freeSpaces = [];

        if ($image->getWidth() < $this->width &&
            $image->getHeight() === $this->height &&
            $this->height >= 0 &&
            ($this->width - $image->getWidth()) > 0
        ) {
            $freeSpaces[] = new self(
                ($this->x + $image->getWidth()),
                $this->y,
                ($this->width - $image->getWidth()),
                $this->height
            );
        }

        if ($image->getWidth() === $this->width &&
            $image->getHeight() < $this->height &&
            $this->width >= 0 &&
            ($this->height - $image->getHeight()) > 0
        ) {
            $freeSpaces[] = new self(
                $this->x,
                ($this->y + $image->getHeight()),
                $this->width,
                ($this->height - $image->getHeight())
            );
        }

        if ($image->getWidth() < $this->width &&
            $image->getHeight() < $this->height &&
            ($this->width - $image->getWidth()) > 0 &&
            ($this->height - $image->getHeight()) > 0
        ) {
            $freeSpaces[] = new self(
                ($this->x + $image->getWidth()),
                $this->y,
                ($this->width - $image->getWidth()),
                $image->getHeight()
            );

            $freeSpaces[] = new self(
                $this->x,
                ($this->y + $image->getHeight()),
                $this->width,
                ($this->height - $image->getHeight())
            );
        }

        return $freeSpaces;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     *
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'size' => [
              'width' => $this->width,
              'height' => $this->height,
            ],
            'position' => [
                'x' => $this->x,
                'y' => $this->y,
            ],
        ];
    }
}
