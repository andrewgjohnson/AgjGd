<?php

/**
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
 */

declare(strict_types=1);

namespace AndrewGJohnson\AgjGd\Tests;

use AndrewGJohnson\AgjGd;
use PHPUnit\Framework\TestCase;

class ImageGradientRectangleTest extends TestCase
{
    private \GdImage $image;

    private int $red;

    private int $blue;

    protected function setUp(): void
    {
        $this->image = imagecreatetruecolor(100, 100);
        $this->red   = (int)imagecolorallocate($this->image, 0xFF, 0x00, 0x00);
        $this->blue  = (int)imagecolorallocate($this->image, 0x00, 0x00, 0xFF);
    }

    public function testVerticalGradientReturnsTrue(): void
    {
        $this->assertTrue(AgjGd::imagegradientrectangle($this->image, 10, 10, 90, 90, $this->red, $this->blue));
    }

    public function testHorizontalGradientReturnsTrue(): void
    {
        $this->assertTrue(AgjGd::imagegradientrectangle($this->image, 10, 10, 90, 90, $this->red, $this->blue, true));
    }

    public function testZeroHeightVerticalGradientReturnsFalse(): void
    {
        $this->assertFalse(AgjGd::imagegradientrectangle($this->image, 10, 50, 90, 50, $this->red, $this->blue));
    }

    public function testZeroWidthHorizontalGradientReturnsFalse(): void
    {
        $this->assertFalse(AgjGd::imagegradientrectangle($this->image, 50, 10, 50, 90, $this->red, $this->blue, true));
    }

    public function testSolidFillReturnsTrue(): void
    {
        $this->assertTrue(AgjGd::imagegradientrectangle($this->image, 10, 10, 90, 90, $this->red));
    }

    public function testVerticalGradientRunsFromColorToGradientColor(): void
    {
        AgjGd::imagegradientrectangle($this->image, 10, 10, 90, 90, $this->red, $this->blue);

        $top    = imagecolorsforindex($this->image, (int)imagecolorat($this->image, 50, 11));
        $bottom = imagecolorsforindex($this->image, (int)imagecolorat($this->image, 50, 89));

        $this->assertGreaterThan($top['blue'], $top['red'], 'The top of the rectangle should be mostly red');
        $this->assertGreaterThan($bottom['red'], $bottom['blue'], 'The bottom of the rectangle should be mostly blue');
    }

    public function testHorizontalGradientRunsFromColorToGradientColor(): void
    {
        AgjGd::imagegradientrectangle($this->image, 10, 10, 90, 90, $this->red, $this->blue, true);

        $left  = imagecolorsforindex($this->image, (int)imagecolorat($this->image, 11, 50));
        $right = imagecolorsforindex($this->image, (int)imagecolorat($this->image, 89, 50));

        $this->assertGreaterThan($left['blue'], $left['red'], 'The left of the rectangle should be mostly red');
        $this->assertGreaterThan($right['red'], $right['blue'], 'The right of the rectangle should be mostly blue');
    }

    public function testVerticalGradientReachesTheFinishColorAtTheFarEdge(): void
    {
        // Regression test for the off-by-one that stopped one pixel short of $y2 and never reached
        // ratio 0, so the far edge must now be painted with the pure finish color (blue).
        AgjGd::imagegradientrectangle($this->image, 10, 10, 90, 90, $this->red, $this->blue);

        $components = imagecolorsforindex($this->image, (int)imagecolorat($this->image, 50, 90));

        $this->assertSame(0, $components['red']);
        $this->assertSame(0, $components['green']);
        $this->assertSame(255, $components['blue']);
    }

    public function testHorizontalGradientReachesTheFinishColorAtTheFarEdge(): void
    {
        // Regression test for the off-by-one that stopped one pixel short of $x2 and never reached
        // ratio 0, so the far edge must now be painted with the pure finish color (blue).
        AgjGd::imagegradientrectangle($this->image, 10, 10, 90, 90, $this->red, $this->blue, true);

        $components = imagecolorsforindex($this->image, (int)imagecolorat($this->image, 90, 50));

        $this->assertSame(0, $components['red']);
        $this->assertSame(0, $components['green']);
        $this->assertSame(255, $components['blue']);
    }

    public function testSolidFillUsesTheColorExactly(): void
    {
        AgjGd::imagegradientrectangle($this->image, 10, 10, 90, 90, $this->red);

        $components = imagecolorsforindex($this->image, (int)imagecolorat($this->image, 50, 50));

        $this->assertSame(255, $components['red']);
        $this->assertSame(0, $components['green']);
        $this->assertSame(0, $components['blue']);
    }

    public function testReturnsFalseWhenTheVerticalGradientExhaustsThePalette(): void
    {
        // A palette image caps at 256 colors; a 400px vertical gradient needs more, so partway through the loop
        // imageblendedcolorallocate() can no longer allocate a color and the method reports the failure.
        $image = imagecreate(400, 400);
        $this->assertNotFalse($image);
        $red  = (int)imagecolorallocate($image, 0xFF, 0x00, 0x00);
        $blue = (int)imagecolorallocate($image, 0x00, 0x00, 0xFF);

        $this->assertFalse(AgjGd::imagegradientrectangle($image, 0, 0, 399, 399, $red, $blue));
    }

    public function testReturnsFalseWhenTheHorizontalGradientExhaustsThePalette(): void
    {
        $image = imagecreate(400, 400);
        $this->assertNotFalse($image);
        $red  = (int)imagecolorallocate($image, 0xFF, 0x00, 0x00);
        $blue = (int)imagecolorallocate($image, 0x00, 0x00, 0xFF);

        $this->assertFalse(AgjGd::imagegradientrectangle($image, 0, 0, 399, 399, $red, $blue, true));
    }
}
