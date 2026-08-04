<?php

/**
 * AgjGd Example (imageblendedcolorallocate: Alpha)
 *
 * Copyright (c) 2018–2026 Andrew G. Johnson <andrew@andrewgjohnson.com>
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
 * Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * PHP version 8
 *
 * @category  AndrewGJohnson
 * @package   AgjGd
 * @author    Andrew G. Johnson <andrew@andrewgjohnson.com>
 * @copyright 2018–2026 Andrew G. Johnson <andrew@andrewgjohnson.com>
 * @license   https://opensource.org/licenses/mit/ The MIT License
 * @link      https://github.com/andrewgjohnson/AgjGd
 */

use AndrewGJohnson\AgjGd;

// Load the AgjGd class directly from source, or fall back to the Composer autoloader
if (file_exists('../../source/AndrewGJohnson/AgjGd.php')) {
    require_once '../../source/AndrewGJohnson/AgjGd.php';
} elseif (file_exists('../../vendor/autoload.php')) {
    require_once '../../vendor/autoload.php';
} elseif (!class_exists('AndrewGJohnson\AgjGd')) {
    die('AndrewGJohnson\AgjGd class not found');
}

// Set the parameters for our image
$width           = 600;
$height          = 300;
$offset          = (int)round($width / 10);
$rectangleWidth  = $offset * 2;
$rectangleHeight = $height - ($offset * 2);

// Create our image
$im = imagecreatetruecolor($width, $height);

if ($im === false) {
    die('Could not create the image');
}

// Set our image’s colors, blending an opaque black with a translucent black
$backgroundColor  = (int)imagecolorallocate($im, 0xEE, 0xEE, 0xEE);
$opaqueBlack      = (int)imagecolorallocatealpha($im, 0x00, 0x00, 0x00, 0);
$translucentBlack = (int)imagecolorallocatealpha($im, 0x00, 0x00, 0x00, 63);
$blendedBlack     = (int)AgjGd::imageblendedcolorallocate($im, $opaqueBlack, $translucentBlack);

// Fill our image with the background color
imagefill($im, 0, 0, $backgroundColor);

// Fill our image with both colors and the new blended color
imagefilledrectangle(
    $im,
    $offset,
    $offset,
    $offset + $rectangleWidth,
    $offset + $rectangleHeight,
    $opaqueBlack
);

imagefilledrectangle(
    $im,
    ($offset * 2) + $rectangleWidth,
    $offset,
    ($offset + $rectangleWidth) * 2,
    $offset + $rectangleHeight,
    $blendedBlack
);

imagefilledrectangle(
    $im,
    ($offset * 3) + ($rectangleWidth * 2),
    $offset,
    ($offset + $rectangleWidth) * 3,
    $offset + $rectangleHeight,
    $translucentBlack
);

// Display our image
header('Content-Type: image/png');
imagepng($im);
