<?php

/**
 * AgjGd v2.0.0
 *
 * Copyright (c) 2013–2026 Andrew G. Johnson <andrew@andrewgjohnson.com>
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
 * @copyright 2013–2026 Andrew G. Johnson <andrew@andrewgjohnson.com>
 * @license   https://opensource.org/licenses/mit/ The MIT License
 * @link      https://github.com/andrewgjohnson/AgjGd
 */

declare(strict_types=1);

namespace AndrewGJohnson;

use GdImage;
use InvalidArgumentException;

/**
 * AgjGd is a project that extends the functionality of PHP’s GD library started by Andrew G. Johnson.
 *
 * Examples:
 *
 * ```
 * <?php
 *
 * use AndrewGJohnson\AgjGd;
 *
 * AgjGd::imageblendedcolorallocate($image, $red, $yellow);
 * AgjGd::imagecolorallocatefromstring($image, '#ff0000');
 * AgjGd::imagegradientrectangle($image, 10, 10, 100, 100, $red, $blue);
 * AgjGd::imagefttextfilter($image, 20, 0, 0, 0, $color, $font, $text, [], 10);
 * AgjGd::imagefttextgradient($image, 20, 0, 0, 0, $color, $font, $text, [], $gradientColor);
 * AgjGd::linebreaksfortext(20, 0, $font, $text, 480);
 * ```
 *
 * @category AndrewGJohnson
 * @package  AgjGd
 * @author   Andrew G. Johnson <andrew@andrewgjohnson.com>
 * @license  https://opensource.org/licenses/mit/ The MIT License
 * @link     https://github.com/andrewgjohnson/AgjGd
 */
class AgjGd
{
    /**
     * The lowest possible GD alpha value, which is completely opaque.
     */
    private const ALPHA_OPAQUE = 0;

    /**
     * The highest possible GD alpha value, which is completely transparent.
     */
    private const ALPHA_TRANSPARENT = 127;

    /**
     * The highest possible value for a single 8-bit RGB color component.
     */
    private const COLOR_COMPONENT_MAX = 255;

    /**
     * The CSS color keywords recognized by imagecolorallocatefromstring() and their RGB values.
     *
     * Source: https://www.w3.org/wiki/CSS/Properties/color/keywords
     *
     * @var array<string, array{0: int, 1: int, 2: int}>
     */
    private const CSS_COLOR_KEYWORDS = [
        'aliceblue'            => [0xF0, 0xF8, 0xFF],
        'antiquewhite'         => [0xFA, 0xEB, 0xD7],
        'aqua'                 => [0x00, 0xFF, 0xFF],
        'aquamarine'           => [0x7F, 0xFF, 0xD4],
        'azure'                => [0xF0, 0xFF, 0xFF],
        'beige'                => [0xF5, 0xF5, 0xDC],
        'bisque'               => [0xFF, 0xE4, 0xC4],
        'black'                => [0x00, 0x00, 0x00],
        'blanchedalmond'       => [0xFF, 0xEB, 0xCD],
        'blue'                 => [0x00, 0x00, 0xFF],
        'blueviolet'           => [0x8A, 0x2B, 0xE2],
        'brown'                => [0xA5, 0x2A, 0x2A],
        'burlywood'            => [0xDE, 0xB8, 0x87],
        'cadetblue'            => [0x5F, 0x9E, 0xA0],
        'chartreuse'           => [0x7F, 0xFF, 0x00],
        'chocolate'            => [0xD2, 0x69, 0x1E],
        'coral'                => [0xFF, 0x7F, 0x50],
        'cornflowerblue'       => [0x64, 0x95, 0xED],
        'cornsilk'             => [0xFF, 0xF8, 0xDC],
        'crimson'              => [0xDC, 0x14, 0x3C],
        'cyan'                 => [0x00, 0xFF, 0xFF],
        'darkblue'             => [0x00, 0x00, 0x8B],
        'darkcyan'             => [0x00, 0x8B, 0x8B],
        'darkgoldenrod'        => [0xB8, 0x86, 0x0B],
        'darkgray'             => [0xA9, 0xA9, 0xA9],
        'darkgreen'            => [0x00, 0x64, 0x00],
        'darkgrey'             => [0xA9, 0xA9, 0xA9],
        'darkkhaki'            => [0xBD, 0xB7, 0x6B],
        'darkmagenta'          => [0x8B, 0x00, 0x8B],
        'darkolivegreen'       => [0x55, 0x6B, 0x2F],
        'darkorange'           => [0xFF, 0x8C, 0x00],
        'darkorchid'           => [0x99, 0x32, 0xCC],
        'darkred'              => [0x8B, 0x00, 0x00],
        'darksalmon'           => [0xE9, 0x96, 0x7A],
        'darkseagreen'         => [0x8F, 0xBC, 0x8F],
        'darkslateblue'        => [0x48, 0x3D, 0x8B],
        'darkslategray'        => [0x2F, 0x4F, 0x4F],
        'darkslategrey'        => [0x2F, 0x4F, 0x4F],
        'darkturquoise'        => [0x00, 0xCE, 0xD1],
        'darkviolet'           => [0x94, 0x00, 0xD3],
        'deeppink'             => [0xFF, 0x14, 0x93],
        'deepskyblue'          => [0x00, 0xBF, 0xFF],
        'dimgray'              => [0x69, 0x69, 0x69],
        'dimgrey'              => [0x69, 0x69, 0x69],
        'dodgerblue'           => [0x1E, 0x90, 0xFF],
        'firebrick'            => [0xB2, 0x22, 0x22],
        'floralwhite'          => [0xFF, 0xFA, 0xF0],
        'forestgreen'          => [0x22, 0x8B, 0x22],
        'fuchsia'              => [0xFF, 0x00, 0xFF],
        'gainsboro'            => [0xDC, 0xDC, 0xDC],
        'ghostwhite'           => [0xF8, 0xF8, 0xFF],
        'gold'                 => [0xFF, 0xD7, 0x00],
        'goldenrod'            => [0xDA, 0xA5, 0x20],
        'gray'                 => [0x80, 0x80, 0x80],
        'green'                => [0x00, 0x80, 0x00],
        'greenyellow'          => [0xAD, 0xFF, 0x2F],
        'grey'                 => [0x80, 0x80, 0x80],
        'honeydew'             => [0xF0, 0xFF, 0xF0],
        'hotpink'              => [0xFF, 0x69, 0xB4],
        'indianred'            => [0xCD, 0x5C, 0x5C],
        'indigo'               => [0x4B, 0x00, 0x82],
        'ivory'                => [0xFF, 0xFF, 0xF0],
        'khaki'                => [0xF0, 0xE6, 0x8C],
        'lavender'             => [0xE6, 0xE6, 0xFA],
        'lavenderblush'        => [0xFF, 0xF0, 0xF5],
        'lawngreen'            => [0x7C, 0xFC, 0x00],
        'lemonchiffon'         => [0xFF, 0xFA, 0xCD],
        'lightblue'            => [0xAD, 0xD8, 0xE6],
        'lightcoral'           => [0xF0, 0x80, 0x80],
        'lightcyan'            => [0xE0, 0xFF, 0xFF],
        'lightgoldenrodyellow' => [0xFA, 0xFA, 0xD2],
        'lightgray'            => [0xD3, 0xD3, 0xD3],
        'lightgreen'           => [0x90, 0xEE, 0x90],
        'lightgrey'            => [0xD3, 0xD3, 0xD3],
        'lightpink'            => [0xFF, 0xB6, 0xC1],
        'lightsalmon'          => [0xFF, 0xA0, 0x7A],
        'lightseagreen'        => [0x20, 0xB2, 0xAA],
        'lightskyblue'         => [0x87, 0xCE, 0xFA],
        'lightslategray'       => [0x77, 0x88, 0x99],
        'lightslategrey'       => [0x77, 0x88, 0x99],
        'lightsteelblue'       => [0xB0, 0xC4, 0xDE],
        'lightyellow'          => [0xFF, 0xFF, 0xE0],
        'lime'                 => [0x00, 0xFF, 0x00],
        'limegreen'            => [0x32, 0xCD, 0x32],
        'linen'                => [0xFA, 0xF0, 0xE6],
        'magenta'              => [0xFF, 0x00, 0xFF],
        'maroon'               => [0x80, 0x00, 0x00],
        'mediumaquamarine'     => [0x66, 0xCD, 0xAA],
        'mediumblue'           => [0x00, 0x00, 0xCD],
        'mediumorchid'         => [0xBA, 0x55, 0xD3],
        'mediumpurple'         => [0x93, 0x70, 0xDB],
        'mediumseagreen'       => [0x3C, 0xB3, 0x71],
        'mediumslateblue'      => [0x7B, 0x68, 0xEE],
        'mediumspringgreen'    => [0x00, 0xFA, 0x9A],
        'mediumturquoise'      => [0x48, 0xD1, 0xCC],
        'mediumvioletred'      => [0xC7, 0x15, 0x85],
        'midnightblue'         => [0x19, 0x19, 0x70],
        'mintcream'            => [0xF5, 0xFF, 0xFA],
        'mistyrose'            => [0xFF, 0xE4, 0xE1],
        'moccasin'             => [0xFF, 0xE4, 0xB5],
        'navajowhite'          => [0xFF, 0xDE, 0xAD],
        'navy'                 => [0x00, 0x00, 0x80],
        'oldlace'              => [0xFD, 0xF5, 0xE6],
        'olive'                => [0x80, 0x80, 0x00],
        'olivedrab'            => [0x6B, 0x8E, 0x23],
        'orange'               => [0xFF, 0xA5, 0x00],
        'orangered'            => [0xFF, 0x45, 0x00],
        'orchid'               => [0xDA, 0x70, 0xD6],
        'palegoldenrod'        => [0xEE, 0xE8, 0xAA],
        'palegreen'            => [0x98, 0xFB, 0x98],
        'paleturquoise'        => [0xAF, 0xEE, 0xEE],
        'palevioletred'        => [0xDB, 0x70, 0x93],
        'papayawhip'           => [0xFF, 0xEF, 0xD5],
        'peachpuff'            => [0xFF, 0xDA, 0xB9],
        'peru'                 => [0xCD, 0x85, 0x3F],
        'pink'                 => [0xFF, 0xC0, 0xCB],
        'plum'                 => [0xDD, 0xA0, 0xDD],
        'powderblue'           => [0xB0, 0xE0, 0xE6],
        'purple'               => [0x80, 0x00, 0x80],
        'red'                  => [0xFF, 0x00, 0x00],
        'rosybrown'            => [0xBC, 0x8F, 0x8F],
        'royalblue'            => [0x41, 0x69, 0xE1],
        'saddlebrown'          => [0x8B, 0x45, 0x13],
        'salmon'               => [0xFA, 0x80, 0x72],
        'sandybrown'           => [0xF4, 0xA4, 0x60],
        'seagreen'             => [0x2E, 0x8B, 0x57],
        'seashell'             => [0xFF, 0xF5, 0xEE],
        'sienna'               => [0xA0, 0x52, 0x2D],
        'silver'               => [0xC0, 0xC0, 0xC0],
        'skyblue'              => [0x87, 0xCE, 0xEB],
        'slateblue'            => [0x6A, 0x5A, 0xCD],
        'slategray'            => [0x70, 0x80, 0x90],
        'slategrey'            => [0x70, 0x80, 0x90],
        'snow'                 => [0xFF, 0xFA, 0xFA],
        'springgreen'          => [0x00, 0xFF, 0x7F],
        'steelblue'            => [0x46, 0x82, 0xB4],
        'tan'                  => [0xD2, 0xB4, 0x8C],
        'teal'                 => [0x00, 0x80, 0x80],
        'thistle'              => [0xD8, 0xBF, 0xD8],
        'tomato'               => [0xFF, 0x63, 0x47],
        'turquoise'            => [0x40, 0xE0, 0xD0],
        'violet'               => [0xEE, 0x82, 0xEE],
        'wheat'                => [0xF5, 0xDE, 0xB3],
        'white'                => [0xFF, 0xFF, 0xFF],
        'whitesmoke'           => [0xF5, 0xF5, 0xF5],
        'yellow'               => [0xFF, 0xFF, 0x00],
        'yellowgreen'          => [0x9A, 0xCD, 0x32]
    ];

    /**
     * Allocates a new blended color based on two existing allocated colors.
     *
     * Examples:
     * ```
     * <?php
     *
     * use AndrewGJohnson\AgjGd;
     *
     * // Allocate red and yellow using the standard method then blend the two to allocate orange
     * $red    = imagecolorallocate($image, 0xFF, 0x00, 0x00);
     * $yellow = imagecolorallocate($image, 0xFF, 0xFF, 0x00);
     * $orange = AgjGd::imageblendedcolorallocate($image, $red, $yellow);
     *
     * // You can also allocate RGBA colors as well as RGB
     * $opaqueBlack      = imagecolorallocatealpha($image, 0x00, 0x00, 0x00, 0);
     * $translucentBlack = imagecolorallocatealpha($image, 0x00, 0x00, 0x00, 63);
     * $blendedBlack     = AgjGd::imageblendedcolorallocate($image, $opaqueBlack, $translucentBlack);
     *
     * // By default, we allocate with a 50/50 blend where we average the red, blue, green and alpha values for each
     * // color but also support alternative blends
     * $blue              = imagecolorallocate($image, 0x00, 0x00, 0xFF);
     * $cyan              = imagecolorallocate($image, 0x00, 0xFF, 0xFF);
     * $blendedMostlyCyan = AgjGd::imageblendedcolorallocate($image, $blue, $cyan, 0.25); // 25% blue, 75% cyan
     * $blendedEvenly     = AgjGd::imageblendedcolorallocate($image, $blue, $cyan); // 50% blue, 50% cyan
     * $blendedMostlyBlue = AgjGd::imageblendedcolorallocate($image, $blue, $cyan, 0.75); // 75% blue, 25% cyan
     * ```
     *
     * @param GdImage   $image         A GdImage object, returned by one of the image creation functions, such as
     * imagecreatetruecolor().
     * @param int|false $color1        A color identifier created with imagecolorallocate(). Passing FALSE, which is
     * what the GD allocation functions return on failure, returns FALSE.
     * @param int|false $color2        A color identifier created with imagecolorallocate(). Passing FALSE, which is
     * what the GD allocation functions return on failure, returns FALSE.
     * @param float     $opacityColor1 The blend ratio for color1, between 0 and 1. At 1 the result is entirely color1;
     * at 0 it is entirely color2; 0.5 (the default) produces an even blend. Values outside of that range fall back to
     * an even blend.
     *
     * @return int|false Returns a color identifier or FALSE if the allocation failed.
     */
    public static function imageblendedcolorallocate(
        GdImage $image,
        int|false $color1,
        int|false $color2,
        float $opacityColor1 = 0.5
    ): int|false {
        // Return false if either color identifier is invalid.
        if ($color1 === false || $color2 === false) {
            return false;
        }

        // Calculate $opacityColor2 based on $opacityColor1, falling back to an even blend when $opacityColor1 is out
        // of range.
        if ($opacityColor1 < 0 || $opacityColor1 > 1) {
            $opacityColor1 = 0.5;
        }

        $opacityColor2 = 1 - $opacityColor1;

        $componentsColor1 = self::colorComponents($image, $color1);
        $componentsColor2 = self::colorComponents($image, $color2);

        $red = (int)round(
            ($componentsColor1['red'] * $opacityColor1) + ($componentsColor2['red'] * $opacityColor2)
        );

        $green = (int)round(
            ($componentsColor1['green'] * $opacityColor1) + ($componentsColor2['green'] * $opacityColor2)
        );

        $blue = (int)round(
            ($componentsColor1['blue'] * $opacityColor1) + ($componentsColor2['blue'] * $opacityColor2)
        );

        $alpha = (int)round(
            ($componentsColor1['alpha'] * $opacityColor1) + ($componentsColor2['alpha'] * $opacityColor2)
        );

        return self::allocateColor($image, $red, $green, $blue, $alpha);
    }

    /**
     * Allocates a color based on a string.
     *
     * Examples:
     * ```
     * <?php
     *
     * use AndrewGJohnson\AgjGd;
     *
     * // Allocate red with imagecolorallocate() or with imagecolorallocatefromstring() via a string
     * $red           = imagecolorallocate($image, 0xFF, 0x00, 0x00);
     * $redFromString = AgjGd::imagecolorallocatefromstring($image, '#FF0000');
     * $redFromString = AgjGd::imagecolorallocatefromstring($image, '#f00');
     * $redFromString = AgjGd::imagecolorallocatefromstring($image, 'rgb(255 0 0)');
     * $redFromString = AgjGd::imagecolorallocatefromstring($image, 'rgb(255, 0, 0)');
     * $redFromString = AgjGd::imagecolorallocatefromstring($image, 'rgba(255, 0, 0, 1)');
     * $redFromString = AgjGd::imagecolorallocatefromstring($image, 'rgba(255 0 0 / 100%)');
     * $redFromString = AgjGd::imagecolorallocatefromstring($image, 'red');
     * ```
     *
     * @param GdImage $image  A GdImage object, returned by one of the image creation functions, such as
     * imagecreatetruecolor().
     * @param string  $string A string describing the color. You can pass a hex code (e.g. '#ff0000'), an RGB value
     * (e.g. 'rgb(255, 0, 0)'), an RGBA value (e.g. 'rgba(255, 0, 0, 1)') or a CSS color keyword (e.g. 'red').
     * @param int     $alpha  A value between 0 and 127. 0 indicates completely opaque while 127 indicates completely
     * transparent. Default is zero. Ignored when $string is an rgb()/rgba() value that specifies its own alpha.
     *
     * @throws InvalidArgumentException If the $string or $alpha parameter is invalid.
     *
     * @return int|false Returns a color identifier or FALSE if the allocation failed.
     */
    public static function imagecolorallocatefromstring(
        GdImage $image,
        string $string,
        int $alpha = self::ALPHA_OPAQUE
    ): int|false {
        // Convert the string to lowercase and remove surrounding whitespace.
        $string = strtolower(trim($string));

        // Track whether an alpha value was supplied, which forces an RGBA color identifier even when the color
        // is fully opaque.
        $hasAlpha = false;

        if (preg_match('/^#?([a-f0-9]{6}|[a-f0-9]{3})$/', $string) === 1) {
            // Remove the pound/hashtag sign.
            $string = ltrim($string, '#');

            // If a short color code was passed convert it to a full color code.
            if (strlen($string) === 3) {
                $string = str_repeat($string[0], 2) . str_repeat($string[1], 2) . str_repeat($string[2], 2);
            }

            // Transform the hex values to decimal values.
            $red   = (int)hexdec(substr($string, 0, 2));
            $green = (int)hexdec(substr($string, 2, 2));
            $blue  = (int)hexdec(substr($string, 4, 2));
        } elseif (
            preg_match(
                '/^(rgba?)\(([0-9]+)(?:, *| +)([0-9]+)(?:, *| +)([0-9]+)(?:(?:, *| *\/ *)(' .
                '(?:(?:0|1)(?:\.[0-9]+)?|\.[0-9]+)|(?:[0-9]+(?:\.[0-9]+)?%)))?\)$/',
                $string,
                $matches
            ) === 1
        ) {
            // Track whether the caller explicitly passed an alpha value. The alpha group is the last one in the
            // pattern, so when it goes unmatched PHP leaves it out of $matches entirely.
            $hasAlpha = isset($matches[5]);

            // Transform the RGBA values to integers.
            $red   = (int)$matches[2];
            $green = (int)$matches[3];
            $blue  = (int)$matches[4];

            if ($hasAlpha) {
                $alphaValue = $matches[5];

                // If the alpha value is a percentage convert it to a decimal value.
                if (str_ends_with($alphaValue, '%')) {
                    $alphaValue = ((float)substr($alphaValue, 0, -1)) / 100;
                } else {
                    $alphaValue = (float)$alphaValue;
                }

                if ($alphaValue < 0 || $alphaValue > 1) {
                    throw new InvalidArgumentException(
                        'imagecolorallocatefromstring() received an invalid value for $string, input was: ' . $string
                    );
                }

                // Convert CSS opacity to GD alpha. CSS uses 1 as opaque and 0 as transparent while GD uses 0 as
                // opaque and 127 as transparent.
                $alpha = self::ALPHA_TRANSPARENT - (int)round(self::ALPHA_TRANSPARENT * $alphaValue);
            }
        } elseif (isset(self::CSS_COLOR_KEYWORDS[$string])) {
            [$red, $green, $blue] = self::CSS_COLOR_KEYWORDS[$string];
        } else {
            throw new InvalidArgumentException(
                'imagecolorallocatefromstring() received an invalid value for $string, input was: ' . $string
            );
        }

        if (
            $red > self::COLOR_COMPONENT_MAX
            || $green > self::COLOR_COMPONENT_MAX
            || $blue > self::COLOR_COMPONENT_MAX
        ) {
            throw new InvalidArgumentException(
                'imagecolorallocatefromstring() received an invalid value for $string, input was: ' . $string
            );
        }

        if ($alpha < self::ALPHA_OPAQUE || $alpha > self::ALPHA_TRANSPARENT) {
            throw new InvalidArgumentException(
                'imagecolorallocatefromstring() received an invalid value for $alpha, input was: ' . $alpha
            );
        }

        return self::allocateColor($image, $red, $green, $blue, $alpha, $hasAlpha);
    }

    /**
     * Draws a gradient filled rectangle.
     *
     * Examples:
     * ```
     * <?php
     *
     * use AndrewGJohnson\AgjGd;
     *
     * $red  = imagecolorallocate($image, 0xFF, 0x00, 0x00);
     * $blue = imagecolorallocate($image, 0x00, 0x00, 0xFF);
     *
     * // Standard method to draw solid filled rectangles
     * imagefilledrectangle($image, 10, 10, 100, 100, $red);
     *
     * // This will draw a solid filled rectangle too, as no gradient color was passed
     * AgjGd::imagegradientrectangle($image, 10, 10, 100, 100, $red);
     *
     * // This will draw a red-to-blue gradient filled rectangle (vertical gradient)
     * AgjGd::imagegradientrectangle($image, 10, 10, 100, 100, $red, $blue);
     *
     * // This will draw a red-to-blue gradient filled rectangle (horizontal gradient)
     * AgjGd::imagegradientrectangle($image, 10, 10, 100, 100, $red, $blue, true);
     * ```
     *
     * @param GdImage $image              A GdImage object, returned by one of the image creation functions, such as
     * imagecreatetruecolor().
     * @param int     $x1                 x-coordinate for point 1.
     * @param int     $y1                 y-coordinate for point 1.
     * @param int     $x2                 x-coordinate for point 2.
     * @param int     $y2                 y-coordinate for point 2.
     * @param int     $color              The start color. A color identifier created with imagecolorallocate().
     * @param ?int    $gradientColor      The finish color. A color identifier created with imagecolorallocate().
     * Passing NULL, the default, draws a solid filled rectangle in $color.
     * @param bool    $horizontalGradient Whether or not to use a horizontal gradient versus a vertical gradient.
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public static function imagegradientrectangle(
        GdImage $image,
        int $x1,
        int $y1,
        int $x2,
        int $y2,
        int $color,
        ?int $gradientColor = null,
        bool $horizontalGradient = false
    ): bool {
        // Without a gradient color there is no gradient to draw, so fall back to a solid filled rectangle.
        if ($gradientColor === null) {
            return imagefilledrectangle($image, $x1, $y1, $x2, $y2, $color);
        }

        if ($horizontalGradient) {
            $left  = min($x1, $x2);
            $width = max($x1, $x2) - $left;

            if ($width <= 0) {
                return false;
            }

            for ($offset = 0; $offset <= $width; $offset++) {
                $offsetColor = self::imageblendedcolorallocate(
                    $image,
                    $color,
                    $gradientColor,
                    1 - ($offset / $width)
                );

                if ($offsetColor === false) {
                    return false;
                }

                imagefilledrectangle($image, $left + $offset, $y1, $left + $offset, $y2, $offsetColor);
            }

            return true;
        }

        $top    = min($y1, $y2);
        $height = max($y1, $y2) - $top;

        if ($height <= 0) {
            return false;
        }

        for ($offset = 0; $offset <= $height; $offset++) {
            $offsetColor = self::imageblendedcolorallocate(
                $image,
                $color,
                $gradientColor,
                1 - ($offset / $height)
            );

            if ($offsetColor === false) {
                return false;
            }

            imagefilledrectangle($image, $x1, $top + $offset, $x2, $top + $offset, $offsetColor);
        }

        return true;
    }

    /**
     * A drop-in replacement for imagettftext() with added parameters to add filtered text enabling blur, glow and
     * shadow effects on your PHP GD images.
     *
     * Examples:
     * ```
     * <?php
     *
     * use AndrewGJohnson\AgjGd;
     *
     * imagettftext($image, 20, 0, 0, 0, $color, $font, $text, []); // Add text to a GD image
     * AgjGd::imagefttextfilter($image, 20, 0, 0, 0, $color, $font, $text, []); // Works the same as the line above
     * AgjGd::imagefttextfilter($image, 20, 0, 0, 0, $color, $font, $text, [], 1); // Adds the same text only blurred
     * ```
     *
     * @param GdImage                      $image           A GdImage object, returned by one of the image creation
     * functions, such as imagecreatetruecolor().
     * @param float                        $size            The font size in points.
     * @param float                        $angle           The angle in degrees, with 0 degrees being left-to-right
     * reading text. Higher values represent a counter-clockwise rotation. For example, a value of 90 would result in
     * bottom-to-top reading text.
     * @param int                          $x               The coordinates given by x and y will define the basepoint
     * of the first character (roughly the lower-left corner of the character). This is different from the
     * imagestring(), where x and y define the upper-left corner of the first character. For example, "top left" is 0,
     * 0.
     * @param int                          $y               The y-ordinate. This sets the position of the font’s
     * baseline, not the very bottom of the character.
     * @param int                          $color           The color index. A negative color index disables
     * antialiasing only on the fallback path, when no filter is applied; when a filter is applied only the color’s
     * RGBA components are used. See imagecolorallocate().
     * @param string                       $fontFilename    The path to the TrueType font you wish to use.
     * @param string                       $text            The text string in UTF-8 encoding.
     * @param array{linespacing?: float}   $options         The options passed through to imagettftext(). An array with
     * a linespacing key holding a float value.
     * @param int                          $filterIntensity The number of times you would like to apply the filter to
     * your text. Passing zero (the default) or a negative value applies no filter at all.
     * @param int                          $filter          The filter you would like applied to your text. Defaults to
     * a Gaussian blur. See imagefilter() for the available filters.
     *
     * @return array<int, int>|false Returns an array with 8 elements representing four points making the bounding box
     * of the text. The order of the points is lower left, lower right, upper right, upper left. The points are
     * relative to the text regardless of the angle, so "upper left" means in the top left-hand corner when you see the
     * text horizontally. Returns FALSE on error.
     */
    public static function imagefttextfilter(
        GdImage $image,
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
    ): array|false {
        // Without a filter intensity there is nothing to apply, so fall back to imagettftext().
        if ($filterIntensity <= 0) {
            return imagettftext($image, $size, $angle, $x, $y, $color, $fontFilename, $text, $options);
        }

        $renderedMask = self::renderTextMask($image, $size, $angle, $x, $y, $fontFilename, $text, $options);
        if ($renderedMask === false) {
            return false;
        }

        [$mask] = $renderedMask;

        // Apply the filter to the mask $filterIntensity times.
        for ($pass = 1; $pass <= $filterIntensity; $pass++) {
            imagefilter($mask, $filter);
        }

        $components = self::colorComponents($image, $color);

        // A fully transparent color scales every pixel’s visibility to zero, which leaves the image untouched and
        // makes plotTextMask() report the failure.
        $colorOpacity = (self::ALPHA_TRANSPARENT - $components['alpha']) / self::ALPHA_TRANSPARENT;

        return self::plotTextMask(
            $image,
            $mask,
            static function (int $maskX, int $maskY, float $visibility) use ($image, $components): int|false {
                // The filtered mask fades the glyphs out at their edges, so a pixel’s visibility becomes its opacity.
                return self::allocateColor(
                    $image,
                    $components['red'],
                    $components['green'],
                    $components['blue'],
                    (int)round((1 - $visibility) * self::ALPHA_TRANSPARENT),
                    true
                );
            },
            $colorOpacity
        );
    }

    /**
     * An alias of imagefttextfilter(), using the more common “ttf” spelling of imagettftext().
     *
     * @param GdImage                    $image           A GdImage object, returned by one of the image creation
     * functions, such as imagecreatetruecolor().
     * @param float                      $size            The font size in points.
     * @param float                      $angle           The angle in degrees.
     * @param int                        $x               The x-ordinate of the basepoint of the first character.
     * @param int                        $y               The y-ordinate of the font’s baseline.
     * @param int                        $color           The color index. See imagecolorallocate().
     * @param string                     $fontFilename    The path to the TrueType font you wish to use.
     * @param string                     $text            The text string in UTF-8 encoding.
     * @param array{linespacing?: float} $options         The options passed through to imagettftext().
     * @param int                        $filterIntensity The number of times you would like to apply the filter to your
     * text. Passing zero (the default) or a negative value applies no filter at all.
     * @param int                        $filter          The filter you would like applied to your text. Defaults to a
     * Gaussian blur. See imagefilter() for the available filters.
     *
     * @alias imagefttextfilter()
     *
     * @return array<int, int>|false Returns an array with 8 elements representing four points making the bounding box
     * of the text, or FALSE on error.
     */
    public static function imagettftextfilter(
        GdImage $image,
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
    ): array|false {
        return self::imagefttextfilter(
            $image,
            $size,
            $angle,
            $x,
            $y,
            $color,
            $fontFilename,
            $text,
            $options,
            $filterIntensity,
            $filter
        );
    }

    /**
     * An alias of imagefttextfilter(). The filter defaults to a Gaussian blur, so this is a convenient name for
     * blurring text and for the glow and shadow effects built on top of a blur.
     *
     * @param GdImage                    $image         A GdImage object, returned by one of the image creation
     * functions, such as imagecreatetruecolor().
     * @param float                      $size          The font size in points.
     * @param float                      $angle         The angle in degrees.
     * @param int                        $x             The x-ordinate of the basepoint of the first character.
     * @param int                        $y             The y-ordinate of the font’s baseline.
     * @param int                        $color         The color index. See imagecolorallocate().
     * @param string                     $fontFilename  The path to the TrueType font you wish to use.
     * @param string                     $text          The text string in UTF-8 encoding.
     * @param array{linespacing?: float} $options       The options passed through to imagettftext().
     * @param int                        $blurIntensity The number of times you would like to apply the blur to your
     * text. Passing zero (the default) or a negative value applies no blur at all.
     * @param int                        $blurFilter    The filter you would like applied to your text. Defaults to a
     * Gaussian blur. See imagefilter() for the available filters.
     *
     * @alias imagefttextfilter()
     *
     * @return array<int, int>|false Returns an array with 8 elements representing four points making the bounding box
     * of the text, or FALSE on error.
     */
    public static function imagettftextblur(
        GdImage $image,
        float $size,
        float $angle,
        int $x,
        int $y,
        int $color,
        string $fontFilename,
        string $text,
        array $options = [],
        int $blurIntensity = 0,
        int $blurFilter = IMG_FILTER_GAUSSIAN_BLUR
    ): array|false {
        return self::imagefttextfilter(
            $image,
            $size,
            $angle,
            $x,
            $y,
            $color,
            $fontFilename,
            $text,
            $options,
            $blurIntensity,
            $blurFilter
        );
    }

    /**
     * An alias of imagefttextfilter(), pairing the “ft” spelling with the blur-oriented name. The filter defaults to a
     * Gaussian blur, so this is a convenient name for blurring text and for the glow and shadow effects built on top of
     * a blur.
     *
     * @param GdImage                    $image         A GdImage object, returned by one of the image creation
     * functions, such as imagecreatetruecolor().
     * @param float                      $size          The font size in points.
     * @param float                      $angle         The angle in degrees.
     * @param int                        $x             The x-ordinate of the basepoint of the first character.
     * @param int                        $y             The y-ordinate of the font’s baseline.
     * @param int                        $color         The color index. See imagecolorallocate().
     * @param string                     $fontFilename  The path to the TrueType font you wish to use.
     * @param string                     $text          The text string in UTF-8 encoding.
     * @param array{linespacing?: float} $options       The options passed through to imagettftext().
     * @param int                        $blurIntensity The number of times you would like to apply the blur to your
     * text. Passing zero (the default) or a negative value applies no blur at all.
     * @param int                        $blurFilter    The filter you would like applied to your text. Defaults to a
     * Gaussian blur. See imagefilter() for the available filters.
     *
     * @alias imagefttextfilter()
     *
     * @return array<int, int>|false Returns an array with 8 elements representing four points making the bounding box
     * of the text, or FALSE on error.
     */
    public static function imagefttextblur(
        GdImage $image,
        float $size,
        float $angle,
        int $x,
        int $y,
        int $color,
        string $fontFilename,
        string $text,
        array $options = [],
        int $blurIntensity = 0,
        int $blurFilter = IMG_FILTER_GAUSSIAN_BLUR
    ): array|false {
        return self::imagefttextfilter(
            $image,
            $size,
            $angle,
            $x,
            $y,
            $color,
            $fontFilename,
            $text,
            $options,
            $blurIntensity,
            $blurFilter
        );
    }

    /**
     * A drop-in replacement for imagettftext() with added parameters to add gradient coloring effects.
     *
     * Examples:
     * ```
     * <?php
     *
     * use AndrewGJohnson\AgjGd;
     *
     * imagettftext($image, 20, 0, 0, 0, $color, $font, $text, []); // Add text to a GD image
     * AgjGd::imagefttextgradient($image, 20, 0, 0, 0, $color, $font, $text, []); // Works the same as the line above
     *
     * // This will add the same text only with a vertical gradient instead of a solid color
     * AgjGd::imagefttextgradient($image, 20, 0, 0, 0, $color, $font, $text, [], $gradientColor);
     *
     * // This will add the same text only with a horizontal gradient instead of a solid color
     * AgjGd::imagefttextgradient($image, 20, 0, 0, 0, $color, $font, $text, [], $gradientColor, true);
     * ```
     *
     * @param GdImage                    $image              A GdImage object, returned by one of the image creation
     * functions, such as imagecreatetruecolor().
     * @param float                      $size               The font size in points.
     * @param float                      $angle              The angle in degrees, with 0 degrees being left-to-right
     * reading text. Higher values represent a counter-clockwise rotation. For example, a value of 90 would result in
     * bottom-to-top reading text.
     * @param int                        $x                  The coordinates given by x and y will define the basepoint
     * of the first character (roughly the lower-left corner of the character). This is different from the
     * imagestring(), where x and y define the upper-left corner of the first character. For example, "top left" is 0,
     * 0.
     * @param int                        $y                  The y-ordinate. This sets the position of the font’s
     * baseline, not the very bottom of the character.
     * @param int                        $color              The start color. The color index. A negative color index
     * disables antialiasing only on the fallback path, when no gradient is applied; when a gradient is applied only
     * the color’s RGBA components are used. See imagecolorallocate().
     * @param string                     $fontFilename       The path to the TrueType font you wish to use.
     * @param string                     $text               The text string in UTF-8 encoding.
     * @param array{linespacing?: float} $options            The options passed through to imagettftext(). An array
     * with a linespacing key holding a float value.
     * @param ?int                       $gradientColor      The finish color. A color identifier created with
     * imagecolorallocate(). A negative color index has no effect on antialiasing here; only the color’s RGBA
     * components are used. Passing NULL, the default, draws the text in a solid $color.
     * @param bool                       $horizontalGradient Whether or not to use a horizontal gradient versus a
     * vertical gradient.
     *
     * @return array<int, int>|false Returns an array with 8 elements representing four points making the bounding box
     * of the text. The order of the points is lower left, lower right, upper right, upper left. The points are
     * relative to the text regardless of the angle, so "upper left" means in the top left-hand corner when you see the
     * text horizontally. Returns FALSE on error.
     */
    public static function imagefttextgradient(
        GdImage $image,
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
    ): array|false {
        // Without a gradient color there is no gradient to draw, so fall back to imagettftext().
        if ($gradientColor === null) {
            return imagettftext($image, $size, $angle, $x, $y, $color, $fontFilename, $text, $options);
        }

        $renderedMask = self::renderTextMask($image, $size, $angle, $x, $y, $fontFilename, $text, $options);
        if ($renderedMask === false) {
            return false;
        }

        // The text’s own bounding box is what the gradient is measured against, so a pixel’s position within the text
        // — not within the image — decides its color.
        [$mask, $textBoundingBox] = $renderedMask;

        $textLeft   = min($textBoundingBox[0], $textBoundingBox[6]);
        $textRight  = max($textBoundingBox[2], $textBoundingBox[4]);
        $textTop    = min($textBoundingBox[5], $textBoundingBox[7]);
        $textBottom = max($textBoundingBox[1], $textBoundingBox[3]);
        $textWidth  = $textRight - $textLeft;
        $textHeight = $textBottom - $textTop;

        $components         = self::colorComponents($image, $color);
        $gradientComponents = self::colorComponents($image, $gradientColor);

        return self::plotTextMask(
            $image,
            $mask,
            static function (
                int $maskX,
                int $maskY,
                float $visibility
            ) use (
                $image,
                $components,
                $gradientComponents,
                $horizontalGradient,
                $textLeft,
                $textTop,
                $textWidth,
                $textHeight
            ): int|false {
                // Calculate how far through the gradient this pixel sits, as a value between 0 and 1. Antialiased
                // pixels can spill just outside the text’s bounding box, so the position is clamped to keep the
                // interpolated color in range.
                if ($horizontalGradient) {
                    $gradientPosition = $textWidth > 0 ? ($maskX - $textLeft) / $textWidth : 0.0;
                } else {
                    $gradientPosition = $textHeight > 0 ? ($maskY - $textTop) / $textHeight : 0.0;
                }

                $gradientPosition = min(1.0, max(0.0, $gradientPosition));

                $red = (int)round(
                    ($components['red'] * (1 - $gradientPosition))
                    + ($gradientComponents['red'] * $gradientPosition)
                );

                $green = (int)round(
                    ($components['green'] * (1 - $gradientPosition))
                    + ($gradientComponents['green'] * $gradientPosition)
                );

                $blue = (int)round(
                    ($components['blue'] * (1 - $gradientPosition))
                    + ($gradientComponents['blue'] * $gradientPosition)
                );

                $gradientAlpha = ($components['alpha'] * (1 - $gradientPosition))
                    + ($gradientComponents['alpha'] * $gradientPosition);

                return self::allocateColor(
                    $image,
                    $red,
                    $green,
                    $blue,
                    (int)round(
                        self::ALPHA_TRANSPARENT - (self::ALPHA_TRANSPARENT - $gradientAlpha) * $visibility
                    ),
                    true
                );
            }
        );
    }

    /**
     * An alias of imagefttextgradient(), using the more common “ttf” spelling of imagettftext().
     *
     * @param GdImage                    $image              A GdImage object, returned by one of the image creation
     * functions, such as imagecreatetruecolor().
     * @param float                      $size               The font size in points.
     * @param float                      $angle              The angle in degrees.
     * @param int                        $x                  The x-ordinate of the basepoint of the first character.
     * @param int                        $y                  The y-ordinate of the font’s baseline.
     * @param int                        $color              The start color. See imagecolorallocate().
     * @param string                     $fontFilename       The path to the TrueType font you wish to use.
     * @param string                     $text               The text string in UTF-8 encoding.
     * @param array{linespacing?: float} $options            The options passed through to imagettftext().
     * @param ?int                       $gradientColor      The finish color. Passing NULL, the default, draws the text
     * in a solid $color.
     * @param bool                       $horizontalGradient Whether or not to use a horizontal gradient versus a
     * vertical gradient.
     *
     * @alias imagefttextgradient()
     *
     * @return array<int, int>|false Returns an array with 8 elements representing four points making the bounding box
     * of the text, or FALSE on error.
     */
    public static function imagettftextgradient(
        GdImage $image,
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
    ): array|false {
        return self::imagefttextgradient(
            $image,
            $size,
            $angle,
            $x,
            $y,
            $color,
            $fontFilename,
            $text,
            $options,
            $gradientColor,
            $horizontalGradient
        );
    }

    /**
     * Automatically inserts line breaks into text intended for imagettftext().
     *
     * Examples:
     * ```
     * <?php
     *
     * use AndrewGJohnson\AgjGd;
     *
     * // You can use linebreaksfortext() to add line breaks ("\n") to long strings to help format text when
     * // using imagettftext()
     * $text = 'This is a long sentence that could not fit on a single line.';
     * $textWithLineBreaks = AgjGd::linebreaksfortext(20, 0, $font, $text, (int)(imagesx($image) * 0.8));
     *
     * // This will work but there will be no line breaks so your text will likely overflow horizontally
     * imagettftext($image, 20, 0, (int)(imagesx($image) * 0.1), 0, $color, $font, $text);
     *
     * // This will work and you will not have to worry about text overflowing regardless of string length
     * imagettftext($image, 20, 0, (int)(imagesx($image) * 0.1), 0, $color, $font, $textWithLineBreaks);
     * ```
     *
     * @param float  $size                    The font size in points.
     * @param float  $angle                   The angle in degrees, with 0 degrees being left-to-right reading text.
     * Higher values represent a counter-clockwise rotation. For example, a value of 90 would result in bottom-to-top
     * reading text.
     * @param string $fontFilename            The path to the TrueType font you wish to use.
     * @param string $text                    The text string in UTF-8 encoding.
     * @param int    $maximumWidth            The maximum width (in pixels) a line should be before adding a line
     * break.
     * @param string $lineBreakCharacter      The character(s) to use when adding a line break.
     * @param bool   $attemptToBreakOnHyphens Whether or not to attempt to break words on the hyphen(s) appearing
     * within.
     * @param bool   $forceBreakOnSingleWords Whether or not to force breaks into single words that extend beyond a
     * single line.
     * @param bool   $preventWidows           Whether or not to try to prevent widows which are single words appearing
     * alone on a final line.
     *
     * @return string Returns a string that is nearly identical to $text with the only difference being newly added
     * line breaks.
     */
    public static function linebreaksfortext(
        float $size,
        float $angle,
        string $fontFilename,
        string $text,
        int $maximumWidth,
        string $lineBreakCharacter = PHP_EOL,
        bool $attemptToBreakOnHyphens = false,
        bool $forceBreakOnSingleWords = false,
        bool $preventWidows = false
    ): string {
        // Create an array with all the string’s words.
        $words = explode(' ', $text);

        // Process all words to generate $textWithLineBreaks.
        $textWithLineBreaks = '';

        $currentLine = '';
        foreach ($words as $position => $word) {
            // Place the first word into $currentLine without further processing. If it is too wide, later logic can
            // only force-break it when another word causes the loop to enter the normal processing branch.
            if ($position === 0) {
                $currentLine = $word;
                continue;
            }

            $addedWord = false;

            // Check whether adding the new word to the current line still fits within the maximum width.
            if (self::ttfWidthFits($size, $angle, $fontFilename, $currentLine . ' ' . $word, $maximumWidth)) {
                $currentLine .= ' ' . $word;

                $addedWord = true;
            }

            // If the final word would appear alone on the last line, try moving the previous word down with it.
            if (!$addedWord && $preventWidows && $position === count($words) - 1) {
                $lastSpacePosition = strrpos($currentLine, ' ');

                if ($lastSpacePosition !== false) {
                    $previousLine = substr($currentLine, 0, $lastSpacePosition);
                    $lastWord     = substr($currentLine, $lastSpacePosition + 1);
                    $testLine     = $lastWord . ' ' . $word;

                    if (self::ttfWidthFits($size, $angle, $fontFilename, $testLine, $maximumWidth)) {
                        $textWithLineBreaks .= $previousLine . $lineBreakCharacter;

                        $currentLine = $testLine;

                        $addedWord = true;
                    }
                }
            }

            // Attempt to split the word on hyphens and fit as much of it as possible on the current line.
            if (!$addedWord && $attemptToBreakOnHyphens && str_contains($word, '-')) {
                $hyphenParts = explode('-', $word);
                $rebuiltWord = '';

                foreach ($hyphenParts as $index => $part) {
                    // Rebuild the word progressively, re-adding hyphens between parts.
                    $candidate = ($rebuiltWord === '' ? $part : $rebuiltWord . '-' . $part);

                    if (
                        self::ttfWidthFits(
                            $size,
                            $angle,
                            $fontFilename,
                            $currentLine . ' ' . $candidate,
                            $maximumWidth
                        )
                    ) {
                        $rebuiltWord = $candidate;
                        continue;
                    }

                    // If we have something that fits, commit it.
                    if ($rebuiltWord !== '') {
                        $currentLine .= ' ' . $rebuiltWord . '-';

                        $textWithLineBreaks .= $currentLine . $lineBreakCharacter;

                        // Remaining parts become the next word.
                        $word = implode('-', array_slice($hyphenParts, $index));

                        $currentLine = $word;

                        $addedWord = true;
                    }

                    break;
                }
            }

            if (!$addedWord && $forceBreakOnSingleWords) {
                [$textWithLineBreaks, $currentLine, $addedWord] = self::forceBreakWord(
                    $size,
                    $angle,
                    $fontFilename,
                    $word,
                    $maximumWidth,
                    $lineBreakCharacter,
                    $textWithLineBreaks,
                    $currentLine
                );
            }

            // If the word still has not been added, the text is too wide with the added word, so add a line break and
            // start a new line with only that word.
            if (!$addedWord) {
                $textWithLineBreaks .= $currentLine . $lineBreakCharacter;

                $currentLine = $word;
            }
        }

        // Append the final line to the processed text.
        return $textWithLineBreaks . $currentLine;
    }

    /**
     * An alias of linebreaksfortext(), using the original standalone function’s name.
     *
     * @param float  $size                    The font size in points.
     * @param float  $angle                   The angle in degrees.
     * @param string $fontFilename            The path to the TrueType font you wish to use.
     * @param string $text                    The text string in UTF-8 encoding.
     * @param int    $maximumWidth            The maximum width (in pixels) a line should be before adding a line break.
     * @param string $lineBreakCharacter      The character(s) to use when adding a line break.
     * @param bool   $attemptToBreakOnHyphens Whether or not to attempt to break words on the hyphen(s) appearing
     * within.
     * @param bool   $forceBreakOnSingleWords Whether or not to force breaks into single words that extend beyond a
     * single line.
     * @param bool   $preventWidows           Whether or not to try to prevent widows.
     *
     * @alias linebreaksfortext()
     *
     * @return string Returns $text with line breaks added.
     */
    public static function linebreaks4imagettftext(
        float $size,
        float $angle,
        string $fontFilename,
        string $text,
        int $maximumWidth,
        string $lineBreakCharacter = PHP_EOL,
        bool $attemptToBreakOnHyphens = false,
        bool $forceBreakOnSingleWords = false,
        bool $preventWidows = false
    ): string {
        return self::linebreaksfortext(
            $size,
            $angle,
            $fontFilename,
            $text,
            $maximumWidth,
            $lineBreakCharacter,
            $attemptToBreakOnHyphens,
            $forceBreakOnSingleWords,
            $preventWidows
        );
    }

    /**
     * An alias of linebreaksfortext(), pairing the “4image” naming with the “ft” spelling of imagefttext().
     *
     * @param float  $size                    The font size in points.
     * @param float  $angle                   The angle in degrees.
     * @param string $fontFilename            The path to the TrueType font you wish to use.
     * @param string $text                    The text string in UTF-8 encoding.
     * @param int    $maximumWidth            The maximum width (in pixels) a line should be before adding a line break.
     * @param string $lineBreakCharacter      The character(s) to use when adding a line break.
     * @param bool   $attemptToBreakOnHyphens Whether or not to attempt to break words on the hyphen(s) appearing
     * within.
     * @param bool   $forceBreakOnSingleWords Whether or not to force breaks into single words that extend beyond a
     * single line.
     * @param bool   $preventWidows           Whether or not to try to prevent widows.
     *
     * @alias linebreaksfortext()
     *
     * @return string Returns $text with line breaks added.
     */
    public static function linebreaks4imagefttext(
        float $size,
        float $angle,
        string $fontFilename,
        string $text,
        int $maximumWidth,
        string $lineBreakCharacter = PHP_EOL,
        bool $attemptToBreakOnHyphens = false,
        bool $forceBreakOnSingleWords = false,
        bool $preventWidows = false
    ): string {
        return self::linebreaksfortext(
            $size,
            $angle,
            $fontFilename,
            $text,
            $maximumWidth,
            $lineBreakCharacter,
            $attemptToBreakOnHyphens,
            $forceBreakOnSingleWords,
            $preventWidows
        );
    }

    /**
     * An alias of linebreaksfortext(), using a shorter name for the same behaviour.
     *
     * @param float  $size                    The font size in points.
     * @param float  $angle                   The angle in degrees.
     * @param string $fontFilename            The path to the TrueType font you wish to use.
     * @param string $text                    The text string in UTF-8 encoding.
     * @param int    $maximumWidth            The maximum width (in pixels) a line should be before adding a line break.
     * @param string $lineBreakCharacter      The character(s) to use when adding a line break.
     * @param bool   $attemptToBreakOnHyphens Whether or not to attempt to break words on the hyphen(s) appearing
     * within.
     * @param bool   $forceBreakOnSingleWords Whether or not to force breaks into single words that extend beyond a
     * single line.
     * @param bool   $preventWidows           Whether or not to try to prevent widows.
     *
     * @alias linebreaksfortext()
     *
     * @return string Returns $text with line breaks added.
     */
    public static function linebreaks4text(
        float $size,
        float $angle,
        string $fontFilename,
        string $text,
        int $maximumWidth,
        string $lineBreakCharacter = PHP_EOL,
        bool $attemptToBreakOnHyphens = false,
        bool $forceBreakOnSingleWords = false,
        bool $preventWidows = false
    ): string {
        return self::linebreaksfortext(
            $size,
            $angle,
            $fontFilename,
            $text,
            $maximumWidth,
            $lineBreakCharacter,
            $attemptToBreakOnHyphens,
            $forceBreakOnSingleWords,
            $preventWidows
        );
    }

    /**
     * An alias of linebreaksfortext(), pairing the “for” naming with the “ft” spelling of imagefttext().
     *
     * @param float  $size                    The font size in points.
     * @param float  $angle                   The angle in degrees.
     * @param string $fontFilename            The path to the TrueType font you wish to use.
     * @param string $text                    The text string in UTF-8 encoding.
     * @param int    $maximumWidth            The maximum width (in pixels) a line should be before adding a line break.
     * @param string $lineBreakCharacter      The character(s) to use when adding a line break.
     * @param bool   $attemptToBreakOnHyphens Whether or not to attempt to break words on the hyphen(s) appearing
     * within.
     * @param bool   $forceBreakOnSingleWords Whether or not to force breaks into single words that extend beyond a
     * single line.
     * @param bool   $preventWidows           Whether or not to try to prevent widows.
     *
     * @alias linebreaksfortext()
     *
     * @return string Returns $text with line breaks added.
     */
    public static function linebreaksforimagefttext(
        float $size,
        float $angle,
        string $fontFilename,
        string $text,
        int $maximumWidth,
        string $lineBreakCharacter = PHP_EOL,
        bool $attemptToBreakOnHyphens = false,
        bool $forceBreakOnSingleWords = false,
        bool $preventWidows = false
    ): string {
        return self::linebreaksfortext(
            $size,
            $angle,
            $fontFilename,
            $text,
            $maximumWidth,
            $lineBreakCharacter,
            $attemptToBreakOnHyphens,
            $forceBreakOnSingleWords,
            $preventWidows
        );
    }

    /**
     * An alias of linebreaksfortext(), pairing the “for” naming with the “ttf” spelling of imagettftext().
     *
     * @param float  $size                    The font size in points.
     * @param float  $angle                   The angle in degrees.
     * @param string $fontFilename            The path to the TrueType font you wish to use.
     * @param string $text                    The text string in UTF-8 encoding.
     * @param int    $maximumWidth            The maximum width (in pixels) a line should be before adding a line break.
     * @param string $lineBreakCharacter      The character(s) to use when adding a line break.
     * @param bool   $attemptToBreakOnHyphens Whether or not to attempt to break words on the hyphen(s) appearing
     * within.
     * @param bool   $forceBreakOnSingleWords Whether or not to force breaks into single words that extend beyond a
     * single line.
     * @param bool   $preventWidows           Whether or not to try to prevent widows.
     *
     * @alias linebreaksfortext()
     *
     * @return string Returns $text with line breaks added.
     */
    public static function linebreaksforimagettftext(
        float $size,
        float $angle,
        string $fontFilename,
        string $text,
        int $maximumWidth,
        string $lineBreakCharacter = PHP_EOL,
        bool $attemptToBreakOnHyphens = false,
        bool $forceBreakOnSingleWords = false,
        bool $preventWidows = false
    ): string {
        return self::linebreaksfortext(
            $size,
            $angle,
            $fontFilename,
            $text,
            $maximumWidth,
            $lineBreakCharacter,
            $attemptToBreakOnHyphens,
            $forceBreakOnSingleWords,
            $preventWidows
        );
    }

    /**
     * Breaks a single word that is too wide for one line across as many lines as it needs, hyphenating each segment.
     *
     * @param float  $size               The font size in points.
     * @param float  $angle              The angle in degrees.
     * @param string $fontFilename       The path to the TrueType font you wish to use.
     * @param string $word               The word to force a break into.
     * @param int    $maximumWidth       The maximum width (in pixels) a line should be before adding a line break.
     * @param string $lineBreakCharacter The character(s) to use when adding a line break.
     * @param string $textWithLineBreaks The processed text so far.
     * @param string $currentLine        The line currently being built.
     *
     * @return array{0: string, 1: string, 2: bool} The processed text, the current line and whether the word was
     * added.
     */
    private static function forceBreakWord(
        float $size,
        float $angle,
        string $fontFilename,
        string $word,
        int $maximumWidth,
        string $lineBreakCharacter,
        string $textWithLineBreaks,
        string $currentLine
    ): array {
        // A word is only ever force-broken from the start of a line, so commit whatever the current line already holds
        // and work from an empty one. Every segment below is therefore measured on its own.
        if ($currentLine !== '') {
            $textWithLineBreaks .= $currentLine . $lineBreakCharacter;
        }

        $remainingCharacters = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);

        if ($remainingCharacters === false) {
            // The word cannot be split (invalid UTF-8). The current line was already committed above, so leave the
            // word whole as the new current line and report it as handled — otherwise the caller's !$addedWord
            // fallback appends a second line break against an empty line, producing a spurious blank line.
            return [$textWithLineBreaks, $word, true];
        }

        while (count($remainingCharacters) > 0) {
            // Take as many characters as will fit on a line, one at a time.
            $characterCount = 0;
            $candidateWord  = '';

            foreach ($remainingCharacters as $index => $character) {
                $testCandidateWord = $candidateWord . $character;

                // Every segment but the last one gets a trailing hyphen, so the hyphen has to fit on the line too.
                $hasRemainingCharacters = ($index < count($remainingCharacters) - 1);
                $testLine               = $testCandidateWord . ($hasRemainingCharacters ? '-' : '');

                if (!self::ttfWidthFits($size, $angle, $fontFilename, $testLine, $maximumWidth)) {
                    break;
                }

                $candidateWord = $testCandidateWord;
                $characterCount++;
            }

            // If not even a single character and a hyphen fit on an empty line, no break is possible at all, so take
            // the whole remainder to avoid looping forever.
            if ($candidateWord === '') {
                return [$textWithLineBreaks, implode('', $remainingCharacters), true];
            }

            $remainingCharacters = array_slice($remainingCharacters, $characterCount);

            // The final segment stays on the current line so that the words after it can still join it.
            if (count($remainingCharacters) === 0) {
                return [$textWithLineBreaks, $candidateWord, true];
            }

            // More characters remain, so hyphenate this segment and commit it as its own line.
            $textWithLineBreaks .= $candidateWord . '-' . $lineBreakCharacter;
        }

        return [$textWithLineBreaks, '', true];
    }

    /**
     * Allocates a color, using an RGBA color identifier when the color is translucent and an RGB color identifier
     * otherwise.
     *
     * @param GdImage $image             A GdImage object.
     * @param int     $red               A value between 0 and 255.
     * @param int     $green             A value between 0 and 255.
     * @param int     $blue              A value between 0 and 255.
     * @param int     $alpha             A value between 0 and 127. 0 indicates completely opaque while 127 indicates
     * completely transparent.
     * @param bool    $forceAlphaChannel Whether to allocate an RGBA color identifier even when $alpha is opaque.
     *
     * @return int|false Returns a color identifier or FALSE if the allocation failed.
     */
    private static function allocateColor(
        GdImage $image,
        int $red,
        int $green,
        int $blue,
        int $alpha = self::ALPHA_OPAQUE,
        bool $forceAlphaChannel = false
    ): int|false {
        // GD raises a ValueError for a component outside its range, and the blending and gradient math rounds floats,
        // so every component is clamped here rather than trusted.
        $red   = max(0, min(self::COLOR_COMPONENT_MAX, $red));
        $green = max(0, min(self::COLOR_COMPONENT_MAX, $green));
        $blue  = max(0, min(self::COLOR_COMPONENT_MAX, $blue));
        $alpha = max(self::ALPHA_OPAQUE, min(self::ALPHA_TRANSPARENT, $alpha));

        if ($forceAlphaChannel || $alpha > self::ALPHA_OPAQUE) {
            return imagecolorallocatealpha($image, $red, $green, $blue, $alpha);
        }

        return imagecolorallocate($image, $red, $green, $blue);
    }

    /**
     * Resolves a color identifier into its red, green, blue and alpha components.
     *
     * A negative color index turns off antialiasing in imagettftext(), so the sign is dropped before the components
     * are read.
     *
     * @param GdImage $image A GdImage object.
     * @param int     $color A color identifier created with imagecolorallocate().
     *
     * @return array{red: int, green: int, blue: int, alpha: int} The color’s components.
     */
    private static function colorComponents(GdImage $image, int $color): array
    {
        return imagecolorsforindex($image, abs($color));
    }

    /**
     * Renders white text on a black background in an image the same size as $image.
     *
     * Both imagefttextfilter() and imagefttextgradient() work by rendering the text once into this grayscale mask and
     * then reading each pixel’s brightness back out as that pixel’s visibility, which is what lets them recolor the
     * antialiased edges of the glyphs.
     *
     * @param GdImage                    $image        A GdImage object.
     * @param float                      $size         The font size in points.
     * @param float                      $angle        The angle in degrees.
     * @param int                        $x            The x-ordinate of the basepoint of the first character.
     * @param int                        $y            The y-ordinate of the font’s baseline.
     * @param string                     $fontFilename The path to the TrueType font you wish to use.
     * @param string                     $text         The text string in UTF-8 encoding.
     * @param array{linespacing?: float} $options      The options passed through to imagettftext().
     *
     * @return array{0: GdImage, 1: array<int, int>}|false Returns the mask and the text’s bounding box, as reported by
     * imagettftext(), or FALSE if the mask could not be rendered.
     */
    private static function renderTextMask(
        GdImage $image,
        float $size,
        float $angle,
        int $x,
        int $y,
        string $fontFilename,
        string $text,
        array $options
    ): array|false {
        $mask = imagecreatetruecolor(imagesx($image), imagesy($image));
        // The dimensions come from an existing image so are always >= 1; the only route to false is memory
        // exhaustion, which cannot be triggered deterministically in a test.
        // @codeCoverageIgnoreStart
        if ($mask === false) {
            return false;
        }
        // @codeCoverageIgnoreEnd

        $black = imagecolorallocate($mask, 0x00, 0x00, 0x00);
        $white = imagecolorallocate($mask, 0xFF, 0xFF, 0xFF);
        // imagecolorallocate() only returns false when a palette image runs out of slots, and $mask is a truecolor
        // image with no palette; the literal components cannot be out of range.
        // @codeCoverageIgnoreStart
        if ($black === false || $white === false) {
            return false;
        }
        // @codeCoverageIgnoreEnd

        imagefill($mask, 0, 0, $black);

        $textBoundingBox = imagettftext($mask, $size, $angle, $x, $y, $white, $fontFilename, $text, $options);
        if ($textBoundingBox === false) {
            return false;
        }

        return [$mask, $textBoundingBox];
    }

    /**
     * Copies every visible pixel of a text mask onto an image, coloring each one with $allocatePixelColor.
     *
     * @param GdImage                                $image              A GdImage object.
     * @param GdImage                                $mask               A mask from renderTextMask().
     * @param callable(int, int, float): (int|false) $allocatePixelColor Receives the pixel’s x-ordinate, y-ordinate
     * and visibility, and returns the color identifier to set that pixel to.
     * @param float                                  $visibilityScale    Scales every pixel’s visibility. A scale of
     * zero makes every pixel invisible, which leaves $image untouched and returns FALSE.
     *
     * @return array<int, int>|false Returns the bounding box of the pixels that were set, or FALSE if no pixel was
     * visible.
     */
    private static function plotTextMask(
        GdImage $image,
        GdImage $mask,
        callable $allocatePixelColor,
        float $visibilityScale = 1.0
    ): array|false {
        // The bounding box starts inverted — every coordinate at the opposite extreme — so that the first visible
        // pixel replaces all eight values and each pixel after it can only widen the box.
        $emptyBoundingBox = [
            imagesx($image), // Lower left (x coordinate)
            -1,              // Lower left (y coordinate)
            -1,              // Lower right (x coordinate)
            -1,              // Lower right (y coordinate)
            -1,              // Upper right (x coordinate)
            imagesy($image), // Upper right (y coordinate)
            imagesx($image), // Upper left (x coordinate)
            imagesy($image)  // Upper left (y coordinate)
        ];

        $boundingBox = $emptyBoundingBox;

        $width  = imagesx($mask);
        $height = imagesy($mask);

        for ($maskX = 0; $maskX < $width; $maskX++) {
            for ($maskY = 0; $maskY < $height; $maskY++) {
                // The mask is grayscale, so any one channel is the pixel’s brightness, which is its visibility.
                $brightness = (int)imagecolorat($mask, $maskX, $maskY) & self::COLOR_COMPONENT_MAX;
                $visibility = $brightness / self::COLOR_COMPONENT_MAX * $visibilityScale;

                if ($visibility <= 0) {
                    continue;
                }

                $boundingBox = [
                    min($boundingBox[0], $maskX),
                    max($boundingBox[1], $maskY),
                    max($boundingBox[2], $maskX),
                    max($boundingBox[3], $maskY),
                    max($boundingBox[4], $maskX),
                    min($boundingBox[5], $maskY),
                    min($boundingBox[6], $maskX),
                    min($boundingBox[7], $maskY)
                ];

                $pixelColor = $allocatePixelColor($maskX, $maskY, $visibility);

                if ($pixelColor !== false) {
                    imagesetpixel($image, $maskX, $maskY, $pixelColor);
                }
            }
        }

        // An unchanged bounding box means no pixel was ever visible, which is a failure.
        if ($boundingBox === $emptyBoundingBox) {
            return false;
        }

        return $boundingBox;
    }

    /**
     * Calculates whether a string rendered by imagettftext() fits within a maximum width.
     *
     * A string whose width cannot be measured is treated as fitting, which leaves the text unbroken rather than
     * breaking it at every word.
     *
     * @param float  $size         The font size in points.
     * @param float  $angle        The angle in degrees.
     * @param string $fontFilename The path to the TrueType font you wish to use.
     * @param string $text         The text string in UTF-8 encoding.
     * @param int    $maximumWidth The maximum width in pixels.
     *
     * @return bool Returns TRUE if the text fits within $maximumWidth.
     */
    private static function ttfWidthFits(
        float $size,
        float $angle,
        string $fontFilename,
        string $text,
        int $maximumWidth
    ): bool {
        $boundingBox = imagettfbbox($size, $angle, $fontFilename, $text);

        if ($boundingBox === false) {
            return true;
        }

        $left  = min($boundingBox[0], $boundingBox[2], $boundingBox[4], $boundingBox[6]);
        $right = max($boundingBox[0], $boundingBox[2], $boundingBox[4], $boundingBox[6]);

        return ($right - $left) <= $maximumWidth;
    }
}
