<?php

/**
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
 */

declare(strict_types=1);

namespace AndrewGJohnson\AgjGd\Tests;

use AndrewGJohnson\AgjGd;
use PHPUnit\Framework\TestCase;

class ImageFtTextFilterTest extends TestCase
{
    private const FILTER_INTENSITY = 1;
    private const FONT_ANGLE       = 0;
    private const FONT_PATH        = __DIR__ . '/NotoSans-Regular.ttf';
    private const FONT_SIZE        = 12;
    private const FONT_X           = 10;
    private const FONT_Y           = 50;
    private const IMAGE_WIDTH      = 200;
    private const IMAGE_HEIGHT     = 100;

    public function testReturnsArrayWithFilter(): void
    {
        $image = $this->createImage();

        $result = AgjGd::imagefttextfilter(
            $image,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($image, 0, 0, 0),
            self::FONT_PATH,
            'Hello world!',
            [],
            self::FILTER_INTENSITY
        );

        $this->assertIsArray($result);
        $this->assertCount(8, $result);
    }

    public function testReturnsArrayWithoutFilter(): void
    {
        $image = $this->createImage();

        $result = AgjGd::imagefttextfilter(
            $image,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($image, 0, 0, 0),
            self::FONT_PATH,
            'Hello world!'
        );

        $this->assertIsArray($result);
        $this->assertCount(8, $result);
    }

    public function testBoundingBoxWithinImageBounds(): void
    {
        $image = $this->createImage();

        $result = AgjGd::imagefttextfilter(
            $image,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($image, 0, 0, 0),
            self::FONT_PATH,
            'Hello world!',
            [],
            self::FILTER_INTENSITY
        );

        $this->assertIsArray($result);

        foreach ([0, 2, 4, 6] as $index) {
            $this->assertGreaterThanOrEqual(0, $result[$index]);
            $this->assertLessThanOrEqual(self::IMAGE_WIDTH, $result[$index]);
        }

        foreach ([1, 3, 5, 7] as $index) {
            $this->assertGreaterThanOrEqual(0, $result[$index]);
            $this->assertLessThanOrEqual(self::IMAGE_HEIGHT, $result[$index]);
        }
    }

    public function testFullyTransparentColorReturnsFalse(): void
    {
        $image = $this->createImage();

        $result = AgjGd::imagefttextfilter(
            $image,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocatealpha($image, 0, 0, 0, 127), // 127 = completely transparent
            self::FONT_PATH,
            'Hello world!',
            [],
            self::FILTER_INTENSITY
        );

        $this->assertFalse($result);
    }

    public function testInvalidFontReturnsFalse(): void
    {
        $image = $this->createImage();

        // @ suppresses the GD warning emitted by imagettftext() when the font is missing
        $result = @AgjGd::imagefttextfilter(
            $image,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($image, 0, 0, 0),
            '/nonexistent/font.ttf',
            'Hello world!',
            [],
            self::FILTER_INTENSITY
        );

        $this->assertFalse($result);
    }

    public function testImageIsModifiedByUse(): void
    {
        $image = $this->createImage();

        $backgroundColor = (int)imagecolorallocate($image, 255, 255, 255); // RGB(255,255,255) = white
        imagefill($image, 0, 0, $backgroundColor);

        AgjGd::imagefttextfilter(
            $image,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($image, 0, 0, 0),
            self::FONT_PATH,
            'Hello world!',
            [],
            self::FILTER_INTENSITY
        );

        // At least one pixel somewhere in the image must differ from the background
        $modified = false;
        for ($x = 0; $x < self::IMAGE_WIDTH && !$modified; $x++) {
            for ($y = 0; $y < self::IMAGE_HEIGHT && !$modified; $y++) {
                if (imagecolorat($image, $x, $y) !== $backgroundColor) {
                    $modified = true;
                }
            }
        }

        $this->assertTrue($modified);
    }

    public function testFilterSpreadsTextBeyondTheUnfilteredBoundingBox(): void
    {
        $unfilteredImage = $this->createImage();
        $filteredImage   = $this->createImage();

        $unfiltered = AgjGd::imagefttextfilter(
            $unfilteredImage,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($unfilteredImage, 0, 0, 0),
            self::FONT_PATH,
            'Hello world!'
        );

        $filtered = AgjGd::imagefttextfilter(
            $filteredImage,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($filteredImage, 0, 0, 0),
            self::FONT_PATH,
            'Hello world!',
            [],
            10
        );

        $this->assertIsArray($unfiltered);
        $this->assertIsArray($filtered);

        // A heavy Gaussian blur bleeds the glyphs outwards, so the filtered bounding box must reach further left and
        // right than the bounding box imagettftext() reports for the same unfiltered text.
        $this->assertLessThan($unfiltered[0], $filtered[0], 'The filtered text should reach further left');
        $this->assertGreaterThan($unfiltered[2], $filtered[2], 'The filtered text should reach further right');
    }

    public function testImageTtfTextFilterAliasMatchesTheCanonicalMethod(): void
    {
        $canonical = $this->createImage();
        $alias     = $this->createImage();

        $canonicalBox = AgjGd::imagefttextfilter(
            $canonical,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($canonical, 0, 0, 0),
            self::FONT_PATH,
            'Hello world!',
            [],
            10
        );

        $aliasBox = AgjGd::imagettftextfilter(
            $alias,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($alias, 0, 0, 0),
            self::FONT_PATH,
            'Hello world!',
            [],
            10
        );

        $this->assertSame($canonicalBox, $aliasBox);
        $this->assertTrue($this->imagesAreIdentical($canonical, $alias));
    }

    public function testImageTtfTextBlurAliasMatchesTheCanonicalMethod(): void
    {
        $canonical = $this->createImage();
        $alias     = $this->createImage();

        $canonicalBox = AgjGd::imagefttextfilter(
            $canonical,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($canonical, 0, 0, 0),
            self::FONT_PATH,
            'Hello world!',
            [],
            10
        );

        $aliasBox = AgjGd::imagettftextblur(
            $alias,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($alias, 0, 0, 0),
            self::FONT_PATH,
            'Hello world!',
            [],
            10
        );

        $this->assertSame($canonicalBox, $aliasBox);
        $this->assertTrue($this->imagesAreIdentical($canonical, $alias));
    }

    public function testImageFtTextBlurAliasMatchesTheCanonicalMethod(): void
    {
        $canonical = $this->createImage();
        $alias     = $this->createImage();

        $canonicalBox = AgjGd::imagefttextfilter(
            $canonical,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($canonical, 0, 0, 0),
            self::FONT_PATH,
            'Hello world!',
            [],
            10
        );

        $aliasBox = AgjGd::imagefttextblur(
            $alias,
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_X,
            self::FONT_Y,
            (int)imagecolorallocate($alias, 0, 0, 0),
            self::FONT_PATH,
            'Hello world!',
            [],
            10
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

    private function createImage(): \GdImage
    {
        $image = imagecreatetruecolor(self::IMAGE_WIDTH, self::IMAGE_HEIGHT);

        $this->assertNotFalse($image);

        return $image;
    }
}
