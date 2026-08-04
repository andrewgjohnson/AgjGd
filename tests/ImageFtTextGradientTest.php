<?php

/**
 * Copyright (c) 2017–2026 Andrew G. Johnson <andrew@andrewgjohnson.com>
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
 */

declare(strict_types=1);

namespace AndrewGJohnson\AgjGd\Tests;

use AndrewGJohnson\AgjGd;
use PHPUnit\Framework\TestCase;

class ImageFtTextGradientTest extends TestCase
{
    private const FONT_ANGLE   = 0;
    private const FONT_PATH    = __DIR__ . '/NotoSans-Regular.ttf';
    private const FONT_SIZE    = 40;
    private const FONT_X       = 10;
    private const FONT_Y       = 120;
    private const IMAGE_WIDTH  = 400;
    private const IMAGE_HEIGHT = 200;

    public function testReturnsArrayWithEightElementsOnSuccess(): void
    {
        $image = $this->createImage();

        $result = AgjGd::imagefttextgradient(
            $image,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($image, 0xFF, 0x00, 0x00),
            self::FONT_PATH,
            'Hello',
            [],
            (int)imagecolorallocate($image, 0x00, 0x00, 0xFF)
        );

        $this->assertIsArray($result);
        $this->assertCount(8, $result);
    }

    public function testBoundingBoxCoordinatesAreWithinImageBounds(): void
    {
        $image = $this->createImage();

        $result = AgjGd::imagefttextgradient(
            $image,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($image, 0xFF, 0x00, 0x00),
            self::FONT_PATH,
            'Hello',
            [],
            (int)imagecolorallocate($image, 0x00, 0x00, 0xFF)
        );

        $this->assertIsArray($result);

        // Even indices are x coordinates, odd indices are y coordinates
        for ($index = 0; $index < 8; $index += 2) {
            $this->assertGreaterThanOrEqual(0, $result[$index]);
            $this->assertLessThan(self::IMAGE_WIDTH, $result[$index]);
        }

        for ($index = 1; $index < 8; $index += 2) {
            $this->assertGreaterThanOrEqual(0, $result[$index]);
            $this->assertLessThan(self::IMAGE_HEIGHT, $result[$index]);
        }
    }

    public function testReturnsFalseForInvalidFont(): void
    {
        $image = $this->createImage();

        // @ suppresses the GD warning emitted by imagettftext() when the font is missing
        $result = @AgjGd::imagefttextgradient(
            $image,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($image, 0xFF, 0x00, 0x00),
            '/nonexistent/font.ttf',
            'Hello',
            [],
            (int)imagecolorallocate($image, 0x00, 0x00, 0xFF)
        );

        $this->assertFalse($result);
    }

    public function testHorizontalGradientReturnsArray(): void
    {
        $image = $this->createImage();

        $result = AgjGd::imagefttextgradient(
            $image,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($image, 0xFF, 0x00, 0x00),
            self::FONT_PATH,
            'Hello',
            [],
            (int)imagecolorallocate($image, 0x00, 0x00, 0xFF),
            true
        );

        $this->assertIsArray($result);
        $this->assertCount(8, $result);
    }

    public function testVerticalGradientColorInterpolation(): void
    {
        $image = $this->createImage();

        $result = AgjGd::imagefttextgradient(
            $image,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($image, 0xFF, 0x00, 0x00),
            self::FONT_PATH,
            'HELLO',
            [],
            (int)imagecolorallocate($image, 0x00, 0x00, 0xFF)
        );

        $this->assertIsArray($result);

        $textTop    = min($result[5], $result[7]);
        $textBottom = max($result[1], $result[3]);
        $textLeft   = min($result[0], $result[6]);
        $textRight  = max($result[2], $result[4]);
        $band       = max(1, (int)(($textBottom - $textTop) * 0.15));

        [$topRedSum, $topBlueSum, $topCount] = $this->sumRgbInRegion(
            $image,
            $textLeft,
            $textTop,
            $textRight,
            $textTop + $band
        );

        [$bottomRedSum, $bottomBlueSum, $bottomCount] = $this->sumRgbInRegion(
            $image,
            $textLeft,
            $textBottom - $band,
            $textRight,
            $textBottom
        );

        $this->assertGreaterThan(0, $topCount, 'Expected text pixels near the top of the bounding box');
        $this->assertGreaterThan(0, $bottomCount, 'Expected text pixels near the bottom of the bounding box');
        $this->assertGreaterThan(
            $topBlueSum / $topCount,
            $topRedSum / $topCount,
            'Top pixels should have more red than blue'
        );
        $this->assertGreaterThan(
            $bottomRedSum / $bottomCount,
            $bottomBlueSum / $bottomCount,
            'Bottom pixels should have more blue than red'
        );
    }

    public function testHorizontalGradientColorInterpolation(): void
    {
        $image = $this->createImage();

        $result = AgjGd::imagefttextgradient(
            $image,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($image, 0xFF, 0x00, 0x00),
            self::FONT_PATH,
            'HELLO',
            [],
            (int)imagecolorallocate($image, 0x00, 0x00, 0xFF),
            true
        );

        $this->assertIsArray($result);

        $textTop    = min($result[5], $result[7]);
        $textBottom = max($result[1], $result[3]);
        $textLeft   = min($result[0], $result[6]);
        $textRight  = max($result[2], $result[4]);
        $band       = max(1, (int)(($textRight - $textLeft) * 0.15));

        [$leftRedSum, $leftBlueSum, $leftCount] = $this->sumRgbInRegion(
            $image,
            $textLeft,
            $textTop,
            $textLeft + $band,
            $textBottom
        );

        [$rightRedSum, $rightBlueSum, $rightCount] = $this->sumRgbInRegion(
            $image,
            $textRight - $band,
            $textTop,
            $textRight,
            $textBottom
        );

        $this->assertGreaterThan(0, $leftCount, 'Expected text pixels near the left edge of the bounding box');
        $this->assertGreaterThan(0, $rightCount, 'Expected text pixels near the right edge of the bounding box');
        $this->assertGreaterThan(
            $leftBlueSum / $leftCount,
            $leftRedSum / $leftCount,
            'Left pixels should have more red than blue'
        );
        $this->assertGreaterThan(
            $rightRedSum / $rightCount,
            $rightBlueSum / $rightCount,
            'Right pixels should have more blue than red'
        );
    }

    public function testAlphaColorsAreInterpolated(): void
    {
        $image = $this->createImage();

        $result = AgjGd::imagefttextgradient(
            $image,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocatealpha($image, 0xFF, 0x00, 0x00, 0),
            self::FONT_PATH,
            'Hello',
            [],
            (int)imagecolorallocatealpha($image, 0x00, 0x00, 0xFF, 64)
        );

        $this->assertIsArray($result);
        $this->assertCount(8, $result);
    }

    public function testCalledWithoutGradientColorRendersSolidText(): void
    {
        // Omitting the gradient color falls back to imagettftext(), which draws the text in a solid $color.
        $image = $this->createImage();

        $result = AgjGd::imagefttextgradient(
            $image,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($image, 0xFF, 0x00, 0x00),
            self::FONT_PATH,
            'Hello'
        );

        $this->assertIsArray($result);
        $this->assertCount(8, $result);

        $expected = imagettftext(
            $this->createImage(),
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            0xFF0000,
            self::FONT_PATH,
            'Hello'
        );

        $this->assertSame($expected, $result);
    }

    public function testImageTtfTextGradientAliasMatchesTheCanonicalMethod(): void
    {
        // imagettftextgradient() is an alias of imagefttextgradient(). It must forward every argument, so rendering the
        // same gradient text through each one must produce byte-for-byte identical images and bounding boxes.
        $canonical = $this->createImage();
        $alias     = $this->createImage();

        $canonicalBox = AgjGd::imagefttextgradient(
            $canonical,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($canonical, 0xFF, 0x00, 0x00),
            self::FONT_PATH,
            'Hello',
            [],
            (int)imagecolorallocate($canonical, 0x00, 0x00, 0xFF),
            true
        );

        $aliasBox = AgjGd::imagettftextgradient(
            $alias,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($alias, 0xFF, 0x00, 0x00),
            self::FONT_PATH,
            'Hello',
            [],
            (int)imagecolorallocate($alias, 0x00, 0x00, 0xFF),
            true
        );

        $this->assertSame($canonicalBox, $aliasBox);
        $this->assertTrue($this->imagesAreIdentical($canonical, $alias));
    }

    private function imagesAreIdentical(\GdImage $a, \GdImage $b): bool
    {
        for ($x = 0; $x < self::IMAGE_WIDTH; $x++) {
            for ($y = 0; $y < self::IMAGE_HEIGHT; $y++) {
                if (imagecolorat($a, $x, $y) !== imagecolorat($b, $x, $y)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Scans a rectangular region and sums the red and blue channel values of non-black pixels.
     *
     * @param \GdImage $image The image to scan.
     * @param int      $x1    The x-ordinate of the region’s first point.
     * @param int      $y1    The y-ordinate of the region’s first point.
     * @param int      $x2    The x-ordinate of the region’s second point.
     * @param int      $y2    The y-ordinate of the region’s second point.
     *
     * @return array{0: int, 1: int, 2: int} The summed red channel, the summed blue channel and the pixel count.
     */
    private function sumRgbInRegion(\GdImage $image, int $x1, int $y1, int $x2, int $y2): array
    {
        $redSum  = 0;
        $blueSum = 0;
        $count   = 0;

        for ($x = $x1; $x <= $x2; $x++) {
            for ($y = $y1; $y <= $y2; $y++) {
                $rgb   = (int)imagecolorat($image, $x, $y);
                $red   = ($rgb >> 16) & 0xFF;
                $blue  = $rgb & 0xFF;

                if ($red + $blue > 0) {
                    $redSum  += $red;
                    $blueSum += $blue;
                    $count++;
                }
            }
        }

        return [$redSum, $blueSum, $count];
    }

    private function createImage(): \GdImage
    {
        $image = imagecreatetruecolor(self::IMAGE_WIDTH, self::IMAGE_HEIGHT);

        $this->assertNotFalse($image);

        return $image;
    }
}
