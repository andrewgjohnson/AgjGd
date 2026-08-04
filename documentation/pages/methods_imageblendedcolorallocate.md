---
layout:    default
title:     imageblendedcolorallocate • Methods • AgjGd
permalink: /methods/imageblendedcolorallocate/
---

# [AgjGd](/methods/)::imageblendedcolorallocate

`AgjGd::imageblendedcolorallocate` allocates a new blended color based on two existing allocated colors for your PHP GD images.

```php
public static function imageblendedcolorallocate(
    \GdImage $image,
    int|false $color1,
    int|false $color2,
    float $opacityColor1 = 0.5
): int|false
```

## Parameters

| Parameter | Type | Description |
| --------- | ---- | ----------- |
| `$image` | `\GdImage` | A GdImage object, returned by one of the image creation functions, such as `imagecreatetruecolor`. |
| `$color1` | `int|false` | A color identifier created with `imagecolorallocate`. Passing `FALSE`, which is what the GD allocation functions return on failure, returns `FALSE`. |
| `$color2` | `int|false` | A color identifier created with `imagecolorallocate`. Passing `FALSE`, which is what the GD allocation functions return on failure, returns `FALSE`. |
| `$opacityColor1` | `float` | The blend ratio for `$color1`, between 0 and 1. At 1 the result is entirely `$color1`; at 0 it is entirely `$color2`; 0.5, the default, produces an even blend. A value outside of that range falls back to an even blend. |

## Return Value

Returns a color identifier, or `FALSE` if the allocation failed.

## Usage

```php
<?php

use AndrewGJohnson\AgjGd;

// Blend red and yellow together to allocate orange
$red    = imagecolorallocate($image, 0xFF, 0x00, 0x00);
$yellow = imagecolorallocate($image, 0xFF, 0xFF, 0x00);
$orange = AgjGd::imageblendedcolorallocate($image, $red, $yellow);

// Alpha values are blended too
$opaqueBlack      = imagecolorallocatealpha($image, 0x00, 0x00, 0x00, 0);
$translucentBlack = imagecolorallocatealpha($image, 0x00, 0x00, 0x00, 63);
$blendedBlack     = AgjGd::imageblendedcolorallocate($image, $opaqueBlack, $translucentBlack);

// The blend does not have to be even
$blendedMostlyCyan = AgjGd::imageblendedcolorallocate($image, $blue, $cyan, 0.25); // 25% blue, 75% cyan
$blendedMostlyBlue = AgjGd::imageblendedcolorallocate($image, $blue, $cyan, 0.75); // 75% blue, 25% cyan
```

## Examples

 1. [**Basic**](/methods/imageblendedcolorallocate/basic-example/)
 1. [**Alpha**](/methods/imageblendedcolorallocate/alpha-example/)
 1. [**Opacity**](/methods/imageblendedcolorallocate/opacity-example/)
