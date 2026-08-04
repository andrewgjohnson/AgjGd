---
layout:    default
title:     imagefttextfilter • Methods • AgjGd
permalink: /methods/imagefttextfilter/
---

# [AgjGd](/methods/)::imagefttextfilter

`AgjGd::imagefttextfilter` is a drop-in replacement for imagefttext/imagettftext with added parameters to add filtered text enabling blur, glow and shadow effects on your PHP GD images.

```php
public static function imagefttextfilter(
    \GdImage $image,
    float $size,
    float $angle,
    int $x,
    int $y,
    int $color,
    string $fontFilename,
    string $text,
    array $options = [],
    int $filterIntensity = 0,
    int $filter = IMG_FILTER_GAUSSIAN_BLUR
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
| `$color` | `int` | The color index. See `imagecolorallocate`. |
| `$fontFilename` | `string` | The path to the TrueType font you wish to use. |
| `$text` | `string` | The text string in UTF-8 encoding. |
| `$options` | `array` | The options passed through to `imagettftext`. An array with a `linespacing` key holding a float value. |
| `$filterIntensity` | `int` | The number of times to apply the filter to your text. Zero, the default, applies no filter at all and behaves exactly like `imagettftext`. |
| `$filter` | `int` | The filter to apply to your text. Defaults to a Gaussian blur. See `imagefilter` for the available filters. |

## Return Value

Returns an array with 8 elements representing four points making the bounding box of the text, or `FALSE` on error.

## Aliases

The following methods are aliases of `imagefttextfilter`. Each one takes the same parameters and returns the same value.

 * `AgjGd::imagettftextfilter` — the more common “ttf” spelling of `imagettftext`.
 * `AgjGd::imagettftextblur` — the “ttf” spelling with the blur-oriented name.
 * `AgjGd::imagefttextblur` — the “ft” spelling with the blur-oriented name.

## Usage

```php
<?php

use AndrewGJohnson\AgjGd;

// Add blurred text to a GD image
AgjGd::imagefttextfilter($image, 20, 0, 0, 0, $color, $font, $text, [], 10);

// Without a filter intensity this behaves exactly like imagefttext/imagettftext
AgjGd::imagefttextfilter($image, 20, 0, 0, 0, $color, $font, $text);
```

## Examples

 1. [**Glow**](/methods/imagefttextfilter/glow-example/)
 1. [**Shadow**](/methods/imagefttextfilter/shadow-example/)
 1. [**With Blur**](/methods/imagefttextfilter/with-blur-example/)
 1. [**Without Blur**](/methods/imagefttextfilter/without-blur-example/)
