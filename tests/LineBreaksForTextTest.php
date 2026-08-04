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

class LineBreaksForTextTest extends TestCase
{
    private const FONT_ANGLE = 0;
    private const FONT_PATH  = __DIR__ . '/NotoSans-Regular.ttf';
    private const FONT_SIZE  = 10;

    public function testTextIsReturnedUnchangedWhenItAlreadyFits(): void
    {
        $this->assertSame('Hello world!', $this->lineBreaks('Hello world!', 10000));
    }

    public function testEmptyStringReturnsEmptyString(): void
    {
        $this->assertSame('', $this->lineBreaks('', 10000));
    }

    public function testSingleWordReturnedUnchanged(): void
    {
        // A single word should always be returned unchanged regardless of the maximum width.
        $this->assertSame('Hello', $this->lineBreaks('Hello', 1));
    }

    public function testTextFittingMaximumWidthHasNoLineBreaks(): void
    {
        $result = $this->lineBreaks('Hello world!', 10000);

        $this->assertStringNotContainsString("\n", $result);
        $this->assertStringNotContainsString("\r", $result);
    }

    public function testTextExceedingMaximumWidthGetsLineBreak(): void
    {
        $this->assertStringContainsString("\n", $this->lineBreaks('Hello world!', 1, "\n"));
    }

    public function testCustomLineBreakCharacter(): void
    {
        $result = $this->lineBreaks('Hello world!', 1, '<br>');

        $this->assertStringContainsString('<br>', $result);
        $this->assertStringNotContainsString(PHP_EOL, $result);
    }

    public function testAllWordsArePresentInOutput(): void
    {
        $words  = ['Hello', 'world', 'foo', 'bar'];
        $result = $this->lineBreaks(implode(' ', $words), 1, "\n");

        foreach ($words as $word) {
            $this->assertStringContainsString($word, $result);
        }
    }

    public function testForceBreakOnSingleWordsSplitsLongWord(): void
    {
        // With forceBreakOnSingleWords disabled a long word that does not fit should still appear intact on a line;
        // with the flag enabled the word should be split across lines.
        $longWord  = 'Pneumonoultramicroscopicsilicovolcanoconiosis';
        $halfWidth = (int)($this->textWidth($longWord) / 2);
        $text      = 'A ' . $longWord;

        $withoutForce = $this->lineBreaks($text, $halfWidth, "\n", false, false);
        $withForce    = $this->lineBreaks($text, $halfWidth, "\n", false, true);

        $foundIntact = false;
        foreach (explode("\n", $withoutForce) as $line) {
            if (str_contains($line, $longWord)) {
                $foundIntact = true;
                break;
            }
        }

        $this->assertTrue(
            $foundIntact,
            'Without forceBreakOnSingleWords the long word should appear intact on a single line'
        );

        $this->assertGreaterThan(
            count(explode("\n", $withoutForce)),
            count(explode("\n", $withForce)),
            'With forceBreakOnSingleWords there should be more lines because the word is split'
        );
    }

    public function testAttemptToBreakOnHyphensBreaksAtHyphen(): void
    {
        // maximumWidth is exactly the pixel width of 'A B-', so 'A B-C' overflows and the hyphen-break logic commits
        // 'A B-' and carries 'C' to the next line.
        $text         = 'A B-C';
        $maximumWidth = $this->textWidth('A B-');

        $withHyphens    = $this->lineBreaks($text, $maximumWidth, "\n", true);
        $withoutHyphens = $this->lineBreaks($text, $maximumWidth, "\n", false);

        $this->assertNotSame(
            $withoutHyphens,
            $withHyphens,
            'attemptToBreakOnHyphens should produce a different result when a break at a hyphen is possible'
        );

        $hasLineEndingWithHyphen = false;
        foreach (explode("\n", $withHyphens) as $line) {
            if (str_ends_with($line, '-')) {
                $hasLineEndingWithHyphen = true;
                break;
            }
        }

        $this->assertTrue(
            $hasLineEndingWithHyphen,
            'With attemptToBreakOnHyphens at least one line should end with a trailing hyphen'
        );
    }

    public function testPreventWidowsMovesPreviousWordToLastLine(): void
    {
        // When the last word would appear alone on the final line (a widow), preventWidows should pull the previous
        // word down so the two words share the last line.
        $text = 'Hello world x';

        // Width fits 'Hello world' exactly, so 'Hello world x' overflows and 'x' becomes a widow.
        $maximumWidth = $this->textWidth('Hello world');

        $withoutPrevent = $this->lineBreaks($text, $maximumWidth, "\n", false, false, false);
        $withPrevent    = $this->lineBreaks($text, $maximumWidth, "\n", false, false, true);

        $linesWithout = explode("\n", $withoutPrevent);
        $this->assertSame(
            'x',
            end($linesWithout),
            'Without preventWidows the last word should appear alone on the final line'
        );

        $linesWith = explode("\n", $withPrevent);
        $lastLine  = end($linesWith);

        $this->assertStringContainsString(
            'world',
            $lastLine,
            'With preventWidows the second-to-last word should be moved to the final line'
        );
        $this->assertStringContainsString(
            'x',
            $lastLine,
            'With preventWidows the last word should still appear on the final line'
        );
    }

    public function testEveryLineFitsWithinTheMaximumWidth(): void
    {
        $text         = 'It was the best of times, it was the worst of times, it was the age of wisdom.';
        $maximumWidth = 120;

        foreach (explode("\n", $this->lineBreaks($text, $maximumWidth, "\n")) as $line) {
            $this->assertLessThanOrEqual(
                $maximumWidth,
                $this->textWidth($line),
                'Every line should fit within the maximum width: ' . $line
            );
        }
    }

    public function testForceBreakReturnsTheWholeWordWhenNoSingleCharacterFits(): void
    {
        // With a maximum width smaller than any single glyph, the force-break loop cannot place even one character,
        // so it returns the whole remaining word rather than looping forever.
        $result = $this->lineBreaks('A verylongword', 1, "\n", false, true);

        $this->assertSame("A\nverylongword", $result);
    }

    public function testForceBreakHandlesEmptyWordsFromConsecutiveSpaces(): void
    {
        // A trailing space produces an empty final word; force-breaking an empty word must terminate cleanly.
        $result = $this->lineBreaks('A ', 1, "\n", false, true);

        $this->assertSame("A\n", $result);
    }

    public function testForceBreakGivesUpOnMalformedUtf8(): void
    {
        // Force-breaking splits a word into characters with preg_split() in UTF-8 mode, which fails on malformed
        // input. GD still measures the word, so it is seen as too wide rather than unmeasurable and the force-break
        // path is entered, but the split cannot happen and the word is left whole on its own line.
        $result = $this->lineBreaks("A verylongword\xFF", 1, "\n", false, true);

        $this->assertSame("A\nverylongword\xFF", $result);
    }

    public function testInvalidFontLeavesTextUnbroken(): void
    {
        // When imagettfbbox() cannot measure the text (here, a missing font) the width is treated as fitting, so no
        // line breaks are added. The @ suppresses the GD warning.
        $result = @AgjGd::linebreaksfortext(
            self::FONT_SIZE,
            self::FONT_ANGLE,
            '/nonexistent/font.ttf',
            'Hello world foo',
            100
        );

        $this->assertSame('Hello world foo', $result);
    }

    public function testAliasesMatchTheCanonicalMethod(): void
    {
        // linebreaks4imagettftext(), linebreaks4imagefttext(), linebreaks4text(), linebreaksforimagefttext() and
        // linebreaksforimagettftext() are all aliases of linebreaksfortext(). Each must forward every argument, so
        // wrapping the same text through each one must return an identical string.
        $text  = 'The quick brown fox jumps over the lazy dog';
        $width = 100;

        $canonical = AgjGd::linebreaksfortext(
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_PATH,
            $text,
            $width
        );

        // The input must actually wrap for the comparison to be meaningful.
        $this->assertStringContainsString(PHP_EOL, $canonical);

        $this->assertSame($canonical, AgjGd::linebreaks4imagettftext(
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_PATH,
            $text,
            $width
        ));

        $this->assertSame($canonical, AgjGd::linebreaks4imagefttext(
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_PATH,
            $text,
            $width
        ));

        $this->assertSame($canonical, AgjGd::linebreaks4text(
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_PATH,
            $text,
            $width
        ));

        $this->assertSame($canonical, AgjGd::linebreaksforimagefttext(
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_PATH,
            $text,
            $width
        ));

        $this->assertSame($canonical, AgjGd::linebreaksforimagettftext(
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_PATH,
            $text,
            $width
        ));
    }

    private function lineBreaks(
        string $text,
        int $maximumWidth,
        string $lineBreakCharacter = PHP_EOL,
        bool $attemptToBreakOnHyphens = false,
        bool $forceBreakOnSingleWords = false,
        bool $preventWidows = false
    ): string {
        return AgjGd::linebreaksfortext(
            self::FONT_SIZE,
            self::FONT_ANGLE,
            self::FONT_PATH,
            $text,
            $maximumWidth,
            $lineBreakCharacter,
            $attemptToBreakOnHyphens,
            $forceBreakOnSingleWords,
            $preventWidows
        );
    }

    private function textWidth(string $text): int
    {
        $boundingBox = imagettfbbox(self::FONT_SIZE, self::FONT_ANGLE, self::FONT_PATH, $text);

        $this->assertNotFalse($boundingBox);

        $left  = min($boundingBox[0], $boundingBox[2], $boundingBox[4], $boundingBox[6]);
        $right = max($boundingBox[0], $boundingBox[2], $boundingBox[4], $boundingBox[6]);

        return $right - $left;
    }
}
