<?php

namespace Bjorvack\ImageStacker;

use Bjorvack\ImageStacker\Exceptions\StackCantGrowException;

class Stacker implements \JsonSerializable
{
    private $name;

    private $images = [];

    private $freeSpaces = [];

    private $width = 0;

    private $height = 0;

    /**
     * @var int
     */
    private $maxWidth;

    /**
     * @var int
     */
    private $maxHeight;

    /**
     * @var bool
     */
    private $growVertical;

    /**
     * @var bool
     */
    private $growHorizontal;

    public function __construct($name, $maxWidth = null, $maxHeight = null, $growVertical = true, $growHorizontal = true)
    {
        $this->name = $name;
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
        $this->growVertical = $growVertical;
        $this->growHorizontal = $growHorizontal;
    }

    /**
     * Add an image to the stacker.
     *
     * @param Image $image
     */
    public function addImage(Image $image)
    {
        $this->images[] = $image;
    }

    /**
     * Stacks the images in a single image.
     */
    public function stack()
    {
        usort($this->images, [$this, 'imageSorter']);

        if ($this->width === 0) {
            $this->width = $this->images[0]->getWidth();
            if ($this->maxWidth < $this->width && $this->maxWidth != null) {
                $this->width = $this->maxWidth;
            }
        }

        if ($this->height === 0) {
            $this->height = $this->images[0]->getHeight();
            if ($this->maxHeight < $this->height && $this->maxHeight != null) {
                $this->height = $this->maxHeight;
            }
        }

        $this->freeSpaces[] = new FreeSpace(0, 0, $this->width, $this->height);

        foreach ($this->images as $image) {
            if ($this->hasSpace($image)) {
                $this->putImageInFreeSpace($image);
                continue;
            }

            $this->addFreeSpaceForImage($image);
            $this->putImageInFreeSpace($image);
        }
    }

    /**
     * Is used for sorting the images.
     *
     * @param Image $a
     * @param Image $b
     *
     * @return int
     */
    private function imageSorter(Image $a, Image $b)
    {
        if ($a->getHeight() == $b->getHeight()) {
            if ($a->getWidth() == $b->getWidth()) {
                return 0;
            }
            if ($a->getWidth() > $b->getWidth()) {
                return -1;
            } else {
                return 1;
            }
        }
        if ($a->getHeight() > $b->getHeight()) {
            return -1;
        } else {
            return 1;
        }
    }

    /**
     * Adds free space to the stack to fit the image.
     *
     * @param Image $image
     *
     * @throws StackCantGrowException
     */
    private function addFreeSpaceForImage(Image $image)
    {
        // If the stack can grow horizontal and the max width isn't passed
        if (($this->width + $image->getWidth() <= $this->maxWidth || $this->maxWidth === null) && $this->growHorizontal) {
            $this->width += $image->getWidth();
            $this->freeSpaces[] = new FreeSpace(
                $this->width - $image->getWidth(),
                0,
                $image->getWidth(),
                $this->height
            );

            return;
        }

        // If the stack can grow vertical and the max height isn't passed
        if (($this->height + $image->getHeight() <= $this->maxHeight || $this->maxWidth === null) && $this->growVertical) {
            $this->height += $image->getHeight();
            $this->freeSpaces[] = new FreeSpace(
                0,
                $this->height - $image->getHeight(),
                $this->width,
                $image->getHeight()
            );

            return;
        }

        throw new StackCantGrowException('The image can\'t grow.');
    }

    /**
     * Puts an image in a free space in the stack.
     *
     * @param Image $image
     */
    private function putImageInFreeSpace(Image &$image)
    {
        foreach ($this->freeSpaces as $key => $freeSpace) {
            if ($freeSpace->imageFits($image)) {
                $freeSpaces = $freeSpace->placeImage($image);
                unset($this->freeSpaces[$key]);
                $this->freeSpaces = array_merge($this->freeSpaces, $freeSpaces);

                return;
            }
        }
    }

    /**
     * Check if there's a free space available.
     *
     * @param Image $image
     *
     * @return bool
     */
    private function hasSpace(Image $image)
    {
        foreach ($this->freeSpaces as $freeSpace) {
            if ($freeSpace->imageFits($image)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the maximum width for the final image.
     *
     * @return int|null
     */
    public function getMaxWidth()
    {
        return $this->maxWidth;
    }

    /**
     * Get the maximum height for the final image.
     *
     * @return int|null
     */
    public function getMaxHeight()
    {
        return $this->maxHeight;
    }

    /**
     * Check if the image can grow verticaly.
     *
     * @return bool
     */
    public function canGrowVerticaly()
    {
        return $this->growVertical;
    }

    /**
     * Check if the image can grow horizontaly.
     *
     * @return bool
     */
    public function canGrowHorizontaly()
    {
        return $this->growHorizontal;
    }

    /**
     * Get the dimensions of the stack.
     *
     * @return array
     */
    public function getSize()
    {
        return [
            'width' => $this->width,
            'height' => $this->height,
        ];
    }

    /**
     * Get all the images added to the stack.
     *
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Get all the remaining free spaces.
     *
     * @return array
     */
    public function getFreeSpaces()
    {
        return $this->freeSpaces;
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
            'name' => $this->name,
            'size' => [
                'width' => $this->width,
                'height' => $this->height,
            ],
            'images' => $this->images,
        ];
    }
}
