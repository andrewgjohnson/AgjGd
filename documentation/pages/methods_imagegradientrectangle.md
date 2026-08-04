---
layout:    default
title:     imagegradientrectangle • Methods • AgjGd
permalink: /methods/imagegradientrectangle/
---

# [AgjGd](/methods/)::imagegradientrectangle

`AgjGd::imagegradientrectangle` draws a gradient filled rectangle on your PHP GD images.

```php
public static function imagegradientrectangle(
    \GdImage $image,
    int $x1,
    int $y1,
    int $x2,
    int $y2,
    int $color,
    ?int $gradientColor = null,
    bool $horizontalGradient = false
): bool
```

## Parameters

| Parameter | Type | Description |
| --------- | ---- | ----------- |
| `$image` | `\GdImage` | A GdImage object, returned by one of the image creation functions, such as `imagecreatetruecolor`. |
| `$x1` | `int` | The x-ordinate for point 1. |
| `$y1` | `int` | The y-ordinate for point 1. |
| `$x2` | `int` | The x-ordinate for point 2. |
| `$y2` | `int` | The y-ordinate for point 2. |
| `$color` | `int` | The start color. A color identifier created with `imagecolorallocate`. |
| `$gradientColor` | `?int` | The finish color. A color identifier created with `imagecolorallocate`. `NULL`, the default, draws a solid filled rectangle in `$color` and behaves exactly like `imagefilledrectangle`. |
| `$horizontalGradient` | `bool` | Whether to use a horizontal gradient rather than a vertical one. |

## Return Value

Returns `TRUE` on success or `FALSE` on failure.

## Usage

```php
<?php

use AndrewGJohnson\AgjGd;

// Draw a red-to-blue gradient filled rectangle (vertical gradient)
AgjGd::imagegradientrectangle($image, 10, 10, 100, 100, $red, $blue);

// Draw a red-to-blue gradient filled rectangle (horizontal gradient)
AgjGd::imagegradientrectangle($image, 10, 10, 100, 100, $red, $blue, true);

// Without a gradient color this behaves exactly like imagefilledrectangle
AgjGd::imagegradientrectangle($image, 10, 10, 100, 100, $red);
```

## Examples

 1. [**Basic**](/methods/imagegradientrectangle/basic-example/)
 1. [**Alpha**](/methods/imagegradientrectangle/alpha-example/)
 1. [**Horizontal Gradient**](/methods/imagegradientrectangle/horizontal-gradient-example/)
