#Image stacker
[![Latest Version](https://img.shields.io/github/release/bjorvack/image-stacker.svg?style=flat-square)](https://github.com/bjorvack/image-stacker/releases)
[![](https://img.shields.io/github/issues-raw/badges/shields.svg?maxAge=2592000)](https://github.com/bjorvack/image-stacker/issues)
[![Packagist](https://img.shields.io/packagist/l/doctrine/orm.svg?maxAge=2592000)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/bjorvack/image-stacker.svg?style=flat-square)](https://travis-ci.org/bjorvack/image-stacker)
Image stacker is a package to combine different images to one single image.

##Instalation

```
composer install bjorvack/image-stacker
```

##Usage

###Creating a new stack

Creating a new stack only requires a stack name. Giving the stack additional parameters like max width or height limits the way the stack can grow.

```php
$stacker = new Stacker('name');
$stacker = new Stacker('name', <int maxWidth>, <int maxWidth>, <bool growVertical>, <bool growHorizontal>);
```

###Adding images to the stack

Using the the `addImage` function an `Image` object can be added to the stack.

An Image needs a path and a name attribute. If the width / height aren't provided the size of the file is loaded from the file itself.

```php
$image = new Image('path', 'name', <int width>, <int height>);
$stacker->addImage($image);
```

###Stacking the images

When all the images are added to the stack the `stack` function can be called to organize the images.

```php
$stacker->stack();
$image = Image::createFromStacker($stacker, 'storagepath');
```

###Getting the position of an image in the stack

When the stack function is called the `x` and `y` attributes for the images are set.
You can access the using the `getX` and `getY` functions.

```php
foreach($stacker->getImages() as $image) {
    $image->getY();
    $image->getX();
}
```


