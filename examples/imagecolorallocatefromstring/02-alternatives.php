<?php

/**
 * AgjGd Example (imagecolorallocatefromstring: Alternatives)
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
$width           = 630;
$height          = 300;
$offset          = 25;
$rectangleWidth  = $offset * 2;
$rectangleHeight = $height - ($offset * 2);

// Create our image
$im = imagecreatetruecolor($width, $height);

if ($im === false) {
    die('Could not create the image');
}

// Fill our image with the background color
imagefill($im, 0, 0, (int)imagecolorallocate($im, 0xEE, 0xEE, 0xEE));

// Every one of these allocates the exact same green, so every rectangle below is identical
$greens = [
    (int)imagecolorallocate($im, 0x00, 0xFF, 0x00),
    (int)AgjGd::imagecolorallocatefromstring($im, '#00FF00'),
    (int)AgjGd::imagecolorallocatefromstring($im, '#0f0'),
    (int)AgjGd::imagecolorallocatefromstring($im, 'rgb(0 255 0)'),
    (int)AgjGd::imagecolorallocatefromstring($im, 'rgb(0, 255, 0)'),
    (int)AgjGd::imagecolorallocatefromstring($im, 'rgba(0, 255, 0, 1)'),
    (int)AgjGd::imagecolorallocatefromstring($im, 'rgba(0 255 0 / 100%)'),
    (int)AgjGd::imagecolorallocatefromstring($im, 'lime')
];

// Fill our image with the [identically] colored rectangles
foreach ($greens as $index => $green) {
    imagefilledrectangle(
        $im,
        ($offset * ($index + 1)) + ($rectangleWidth * $index),
        $offset,
        ($offset + $rectangleWidth) * ($index + 1),
        $offset + $rectangleHeight,
        $green
    );
}

// Display our image
header('Content-Type: image/png');
imagepng($im);
