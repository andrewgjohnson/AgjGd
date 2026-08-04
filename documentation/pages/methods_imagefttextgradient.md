---
layout:    default
title:     imagefttextgradient • Methods • AgjGd
permalink: /methods/imagefttextgradient/
---

# [AgjGd](/methods/)::imagefttextgradient

`AgjGd::imagefttextgradient` is a drop-in replacement for imagefttext/imagettftext with added parameters to add gradient coloring effects to your PHP GD images.

```php
public static function imagefttextgradient(
    \GdImage $image,
    float $size,
    float $angle,
    int $x,
    int $y,
    int $color,
    string $fontFilename,
    string $text,
    array $options = [],
    ?int $gradientColor = null,
    bool $horizontalGradient = false
): array|false
```

## Parameters

| Parameter | Type | Description |
| --------- | ---- | ----------- |
| `$image` | `\GdImage` | A GdImage object, returned by one of the image creation functions, such as `imagecreatetruecolor`. |
| `$size` | `float` | The font size in points. |
| `$angle` | `float` | The angle in degrees, with 0 degrees being left-to-right reading text. |
| `$x` | `int` | The x-ordinate of the basepoint of the first character. |
| `$y` | `int` | The y-ordinate of the font’s baseline, not the very bottom of the character. |
| `$color` | `int` | The start color. A color identifier created with `imagecolorallocate`. |
| `$fontFilename` | `string` | The path to the TrueType font you wish to use. |
| `$text` | `string` | The text string in UTF-8 encoding. |
| `$options` | `array` | The options passed through to `imagettftext`. An array with a `linespacing` key holding a float value. |
| `$gradientColor` | `?int` | The finish color. A color identifier created with `imagecolorallocate`. `NULL`, the default, draws the text in a solid `$color` and behaves exactly like `imagettftext`. |
| `$horizontalGradient` | `bool` | Whether to use a horizontal gradient rather than a vertical one. |

## Return Value

Returns an array with 8 elements representing four points making the bounding box of the text, or `FALSE` on error.

## Aliases

The following method is an alias of `imagefttextgradient`. It takes the same parameters and returns the same value.

 * `AgjGd::imagettftextgradient` — the more common “ttf” spelling of `imagettftext`.

## Usage

```php
<?php

use AndrewGJohnson\AgjGd;

// Add text with a vertical gradient to a GD image
AgjGd::imagefttextgradient($image, 20, 0, 0, 0, $color, $font, $text, [], $gradientColor);

// Add text with a horizontal gradient instead
AgjGd::imagefttextgradient($image, 20, 0, 0, 0, $color, $font, $text, [], $gradientColor, true);

// Without a gradient color this behaves exactly like imagefttext/imagettftext
AgjGd::imagefttextgradient($image, 20, 0, 0, 0, $color, $font, $text);
```

## Examples

 1. [**Red to Blue**](/methods/imagefttextgradient/red-to-blue-example/)
 1. [**Yellow to Green**](/methods/imagefttextgradient/yellow-to-green-example/)
 1. [**Horizontal Gradient**](/methods/imagefttextgradient/horizontal-gradient-example/)
