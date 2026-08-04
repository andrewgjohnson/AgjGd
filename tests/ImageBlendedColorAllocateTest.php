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

class ImageBlendedColorAllocateTest extends TestCase
{
    private \GdImage $image;

    protected function setUp(): void
    {
        $this->image = imagecreatetruecolor(100, 100);
    }

    public function testEvenBlend(): void
    {
        $red    = imagecolorallocate($this->image, 0xFF, 0x00, 0x00);
        $yellow = imagecolorallocate($this->image, 0xFF, 0xFF, 0x00);
        $result = AgjGd::imageblendedcolorallocate($this->image, $red, $yellow);

        $this->assertNotFalse($result);

        $components = imagecolorsforindex($this->image, $result);

        $this->assertSame(255, $components['red']);
        $this->assertSame(128, $components['green']); // round(127.5) = 128
        $this->assertSame(0, $components['blue']);
        $this->assertSame(0, $components['alpha']);
    }

    public function testCustomRatioBlend(): void
    {
        $blue   = imagecolorallocate($this->image, 0x00, 0x00, 0xFF);
        $cyan   = imagecolorallocate($this->image, 0x00, 0xFF, 0xFF);
        $result = AgjGd::imageblendedcolorallocate($this->image, $blue, $cyan, 0.25);

        $this->assertNotFalse($result);

        $components = imagecolorsforindex($this->image, $result);

        $this->assertSame(0, $components['red']);
        $this->assertSame(191, $components['green']); // round(255 * 0.75) = 191
        $this->assertSame(255, $components['blue']);
        $this->assertSame(0, $components['alpha']);
    }

    public function testFullColor1Blend(): void
    {
        $red    = imagecolorallocate($this->image, 0xFF, 0x00, 0x00);
        $blue   = imagecolorallocate($this->image, 0x00, 0x00, 0xFF);
        $result = AgjGd::imageblendedcolorallocate($this->image, $red, $blue, 1.0);

        $this->assertNotFalse($result);

        $components = imagecolorsforindex($this->image, $result);

        $this->assertSame(255, $components['red']);
        $this->assertSame(0, $components['green']);
        $this->assertSame(0, $components['blue']);
        $this->assertSame(0, $components['alpha']);
    }

    public function testFullColor2Blend(): void
    {
        $red    = imagecolorallocate($this->image, 0xFF, 0x00, 0x00);
        $blue   = imagecolorallocate($this->image, 0x00, 0x00, 0xFF);
        $result = AgjGd::imageblendedcolorallocate($this->image, $red, $blue, 0.0);

        $this->assertNotFalse($result);

        $components = imagecolorsforindex($this->image, $result);

        $this->assertSame(0, $components['red']);
        $this->assertSame(0, $components['green']);
        $this->assertSame(255, $components['blue']);
        $this->assertSame(0, $components['alpha']);
    }

    public function testOutOfRangeOpacityFallsBackToEvenBlend(): void
    {
        $red    = imagecolorallocate($this->image, 0xFF, 0x00, 0x00);
        $yellow = imagecolorallocate($this->image, 0xFF, 0xFF, 0x00);
        $result = AgjGd::imageblendedcolorallocate($this->image, $red, $yellow, 1.5);

        $this->assertNotFalse($result);

        $components = imagecolorsforindex($this->image, $result);

        $this->assertSame(255, $components['red']);
        $this->assertSame(128, $components['green']); // falls back to 50/50, same as testEvenBlend
        $this->assertSame(0, $components['blue']);
        $this->assertSame(0, $components['alpha']);
    }

    public function testNegativeOpacityFallsBackToEvenBlend(): void
    {
        $red    = imagecolorallocate($this->image, 0xFF, 0x00, 0x00);
        $yellow = imagecolorallocate($this->image, 0xFF, 0xFF, 0x00);
        $result = AgjGd::imageblendedcolorallocate($this->image, $red, $yellow, -0.1);

        $this->assertNotFalse($result);

        $components = imagecolorsforindex($this->image, $result);

        $this->assertSame(255, $components['red']);
        $this->assertSame(128, $components['green']); // falls back to 50/50, same as testEvenBlend
        $this->assertSame(0, $components['blue']);
        $this->assertSame(0, $components['alpha']);
    }

    public function testAlphaBlend(): void
    {
        $opaqueBlack      = imagecolorallocatealpha($this->image, 0x00, 0x00, 0x00, 0);
        $translucentBlack = imagecolorallocatealpha($this->image, 0x00, 0x00, 0x00, 63);
        $result           = AgjGd::imageblendedcolorallocate($this->image, $opaqueBlack, $translucentBlack);

        $this->assertNotFalse($result);

        $components = imagecolorsforindex($this->image, $result);

        $this->assertSame(0, $components['red']);
        $this->assertSame(0, $components['green']);
        $this->assertSame(0, $components['blue']);
        $this->assertSame(32, $components['alpha']); // round(63 * 0.5) = 32
    }

    public function testInvalidColor1ReturnsFalse(): void
    {
        $blue = imagecolorallocate($this->image, 0x00, 0x00, 0xFF);

        $this->assertFalse(AgjGd::imageblendedcolorallocate($this->image, false, $blue));
    }

    public function testInvalidColor2ReturnsFalse(): void
    {
        $red = imagecolorallocate($this->image, 0xFF, 0x00, 0x00);

        $this->assertFalse(AgjGd::imageblendedcolorallocate($this->image, $red, false));
    }
}
