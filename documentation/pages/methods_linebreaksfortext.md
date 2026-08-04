---
layout:    default
title:     linebreaksfortext • Methods • AgjGd
permalink: /methods/linebreaksfortext/
---

# [AgjGd](/methods/)::linebreaksfortext

`AgjGd::linebreaksfortext` automatically inserts line breaks into your text while using PHP’s imagefttext/imagettftext functions.

```php
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
): string
```

## Parameters

| Parameter | Type | Description |
| --------- | ---- | ----------- |
| `$size` | `float` | The font size in points. |
| `$angle` | `float` | The angle in degrees, with 0 degrees being left-to-right reading text. |
| `$fontFilename` | `string` | The path to the TrueType font you wish to use. |
| `$text` | `string` | The text string in UTF-8 encoding. |
| `$maximumWidth` | `int` | The maximum width (in pixels) a line should be before adding a line break. |
| `$lineBreakCharacter` | `string` | The character(s) to use when adding a line break. |
| `$attemptToBreakOnHyphens` | `bool` | Whether to attempt to break words on the hyphen(s) appearing within. |
| `$forceBreakOnSingleWords` | `bool` | Whether to force breaks into single words that extend beyond a single line. |
| `$preventWidows` | `bool` | Whether to try to prevent widows, which are single words appearing alone on a final line. |

## Return Value

Returns a string that is nearly identical to `$text`, the only difference being the newly added line breaks.

## Aliases

The following methods are aliases of `linebreaksfortext`. Each one takes the same parameters and returns the same value.

 * `AgjGd::linebreaks4imagettftext` — the original standalone function’s name.
 * `AgjGd::linebreaks4imagefttext` — the “4image” naming with the “ft” spelling of `imagefttext`.
 * `AgjGd::linebreaks4text` — a shorter name for the same behaviour.
 * `AgjGd::linebreaksforimagefttext` — the spelled-out “for image” naming with the “ft” spelling of `imagefttext`.
 * `AgjGd::linebreaksforimagettftext` — the spelled-out “for image” naming with the “ttf” spelling of `imagettftext`.

## Usage

```php
<?php

use AndrewGJohnson\AgjGd;

$text = 'This is a long sentence that could not fit on a single line.';

$textWithLineBreaks = AgjGd::linebreaksfortext(20, 0, $font, $text, (int)(imagesx($image) * 0.8));

// This will work but there will be no line breaks so your text will likely overflow horizontally
imagettftext($image, 20, 0, 0, 0, $color, $font, $text);

// This will work and you will not have to worry about text overflowing regardless of string length
imagettftext($image, 20, 0, 0, 0, $color, $font, $textWithLineBreaks);
```

## Examples

 1. [**Before and After**](/methods/linebreaksfortext/before-and-after-example/)
 1. [**Using the Parameters**](/methods/linebreaksfortext/using-the-parameters-example/)
