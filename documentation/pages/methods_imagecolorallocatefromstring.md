---
layout:    default
title:     imagecolorallocatefromstring • Methods • AgjGd
permalink: /methods/imagecolorallocatefromstring/
---

# [AgjGd](/methods/)::imagecolorallocatefromstring

`AgjGd::imagecolorallocatefromstring` allocates a color based on a string for your PHP GD images.

```php
public static function imagecolorallocatefromstring(
    \GdImage $image,
    string $string,
    int $alpha = 0
): int|false
```

## Parameters

| Parameter | Type | Description |
| --------- | ---- | ----------- |
| `$image` | `\GdImage` | A GdImage object, returned by one of the image creation functions, such as `imagecreatetruecolor`. |
| `$string` | `string` | A string describing the color. You can pass a hex code (e.g. `#ff0000` or `#f00`), an RGB value (e.g. `rgb(255, 0, 0)`), an RGBA value (e.g. `rgba(255, 0, 0, 1)`) or a [CSS color keyword](https://www.w3.org/wiki/CSS/Properties/color/keywords) (e.g. `red`). |
| `$alpha` | `int` | A value between 0 and 127. 0 indicates completely opaque while 127 indicates completely transparent. Default is zero. |

## Return Value

Returns a color identifier, or `FALSE` if the allocation failed.

## Throws

Throws an `\InvalidArgumentException` if `$string` is not a valid color or if `$alpha` is outside of the 0 to 127 range.

## Usage

```php
<?php

use AndrewGJohnson\AgjGd;

// Every one of these allocates the exact same red
$red = AgjGd::imagecolorallocatefromstring($image, '#FF0000');
$red = AgjGd::imagecolorallocatefromstring($image, '#f00');
$red = AgjGd::imagecolorallocatefromstring($image, 'rgb(255 0 0)');
$red = AgjGd::imagecolorallocatefromstring($image, 'rgb(255, 0, 0)');
$red = AgjGd::imagecolorallocatefromstring($image, 'rgba(255, 0, 0, 1)');
$red = AgjGd::imagecolorallocatefromstring($image, 'rgba(255 0 0 / 100%)');
$red = AgjGd::imagecolorallocatefromstring($image, 'red');
```

## Examples

 1. [**Basic**](/methods/imagecolorallocatefromstring/basic-example/)
 1. [**Alternatives**](/methods/imagecolorallocatefromstring/alternatives-example/)
