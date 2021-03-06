# Image stacker
[![Latest Version](https://img.shields.io/github/release/bjorvack/image-stacker.svg?style=flat-square)](https://github.com/bjorvack/image-stacker/releases)
[![GitHub issues open](https://img.shields.io/github/issues-raw/bjorvack/image-stacker.svg?style=flat-square&maxAge=2592000)](https://github.com/bjorvack/image-stacker/issues)
[![Packagist](https://img.shields.io/packagist/l/doctrine/orm.svg?style=flat-square&maxAge=2592000)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/bjorvack/image-stacker.svg?style=flat-square)](https://travis-ci.org/bjorvack/image-stacker)

Image stacker is a package to combine different images to one single image.

## Instalation

```
composer install bjorvack/image-stacker
```

## Usage

### Creating a new stack

Creating a new stack only requires a stack name. Giving the stack additional parameters like max width or height limits the way the stack can grow.

```php
$stacker = new Stacker('name');
$stacker = new Stacker('name', <int maxWidth>, <int maxWidth>, <bool growVertical>, <bool growHorizontal>);
```

### Adding images to the stack

Using the the `addImage` function an `Image` object can be added to the stack.

An Image needs a path and a name attribute. If the width / height aren't provided the size of the file is loaded from the file itself.

```php
$image = new Image('path', 'name', <int width>, <int height>);
$stacker->addImage($image);
```

### Creating the stacked image

```php
$image = Image::createFromStacker($stacker, 'storagepath');
```

### Getting the position of an image in the stack

When the stack function is called the `x` and `y` attributes for the images are set.
You can access the using the `getX` and `getY` functions.

```php
foreach($stacker->getImages() as $image) {
    $image->getY();
    $image->getX();
}
```

### Exporters

A stack can be exported as a `.json` file or a `.css` file. The `.png` file is made automatically.

```php
$stacker = new Stacker('name');

JsonExporter::save($stacker, 'path');
StylesheetExporter::save($stacker, 'path');
```
