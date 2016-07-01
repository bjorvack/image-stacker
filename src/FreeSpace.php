<?php

namespace Bjorvack\ImageStacker;

use Bjorvack\ImageStacker\Exceptions\CantPlaceImageException;

class FreeSpace
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

        if ($image->getWidth() < $this->width && $image->getHeight() === $this->height) {
            $freeSpaces[] = new self(
                ($this->x + $image->getWidth()),
                $this->y,
                ($this->width - $image->getWidth()),
                $this->height
            );
        }

        if ($image->getWidth() === $this->width && $image->getHeight() < $this->height) {
            $freeSpaces[] = new self(
                $this->x,
                ($this->y + $image->getHeight()),
                $this->width,
                ($this->height - $image->getHeight())
            );
        }

        if ($image->getWidth() < $this->width && $image->getHeight() < $this->height) {
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
}
