# AgjGd

[![MIT License](https://img.shields.io/badge/license-MIT-0366d6.png?colorB=0366d6&style=flat-square)](https://github.com/andrewgjohnson/AgjGd/blob/master/LICENSE)
[![Current Release](https://img.shields.io/github/release/andrewgjohnson/AgjGd.png?colorB=0366d6&style=flat-square&logoColor=white&logo=github)](https://github.com/andrewgjohnson/AgjGd/releases)
[![Coveralls Coverage](https://img.shields.io/coverallsCoverage/github/andrewgjohnson/AgjGd.png?colorB=0366d6&style=flat-square&logoColor=white&logo=coveralls)](https://coveralls.io/github/andrewgjohnson/AgjGd)
[![Packagist Downloads](documentation/images/downloads-badge.png)](https://AgjGd.org/statistics/#downloads)
[![GitHub Stargazers](documentation/images/stargazers-badge.png)](https://AgjGd.org/statistics/#stargazers)
[![GitHub Contributors](documentation/images/contributors-badge.png)](https://AgjGd.org/statistics/#contributors)
[![GitHub Issues](documentation/images/issues-badge.png)](https://AgjGd.org/statistics/#open-issues)
[![Patreon](documentation/images/patreon-badge.png)](https://patreon.com/agjopensource)

<p align="center"><a href="https://AgjGd.org/" title=""><img src="documentation/images/avatar.png" alt="" title="" width="400" id="avatar" /></a></p>

## Description

**AgjGd** is a project that extends the functionality of [PHP](https://www.php.net)’s [GD](https://www.php.net/manual/book.image.php) library started by [Andrew G. Johnson](https://github.com/andrewgjohnson). It is a single class, `\AndrewGJohnson\AgjGd`, with functionality exposed via public static [methods](https://AgjGd.org/methods/).

[![Patreon - Become a Patron](https://raster.shields.io/badge/Patreon%20-become%20a%20Patron-FD334A.png?style=for-the-badge&logo=patreon&logoColor=FD334A)](https://patreon.com/agjopensource)

### Example

```php
<?php

use AndrewGJohnson\AgjGd;

$image = imagecreatetruecolor(600, 300);
$font  = 'NotoSans-Regular.ttf';

// Fill the image with a red-to-blue gradient
AgjGd::imagegradientrectangle(
    $image,
    0,
    0,
    imagesx($image) - 1,
    imagesy($image) - 1,
    AgjGd::imagecolorallocatefromstring($image, 'red'),
    AgjGd::imagecolorallocatefromstring($image, 'blue')
);

// Wrap the text so that it does not overflow, then write it as blurred yellow text
$text = AgjGd::linebreaksfortext(30, 0, $font, 'Hello from AgjGd!', 500);

AgjGd::imagefttextfilter(
    $image,
    30,
    0,
    50,
    150,
    AgjGd::imagecolorallocatefromstring($image, 'yellow'),
    $font,
    $text,
    [],
    10
);

header('Content-Type: image/png');
imagepng($image);
```

You can find [more examples](https://AgjGd.org/examples/) and the [full documentation for every method](https://AgjGd.org/methods/) at [AgjGd.org](https://AgjGd.org/).

## Methods

 * [**AgjGd::imageblendedcolorallocate**](https://AgjGd.org/methods/imageblendedcolorallocate/) allocates a new blended color based on two existing allocated colors for your PHP GD images.
 * [**AgjGd::imagecolorallocatefromstring**](https://AgjGd.org/methods/imagecolorallocatefromstring/) allocates a color based on a string for your PHP GD images.
 * [**AgjGd::imagefttextfilter**](https://AgjGd.org/methods/imagefttextfilter/) is a drop-in replacement for `imagefttext`/`imagettftext` with added parameters to add filtered text enabling blur, glow and shadow effects on your PHP GD images.
 * [**AgjGd::imagefttextgradient**](https://AgjGd.org/methods/imagefttextgradient/) is a drop-in replacement for `imagefttext`/`imagettftext` with added parameters to add gradient coloring effects to your PHP GD images.
 * [**AgjGd::imagegradientrectangle**](https://AgjGd.org/methods/imagegradientrectangle/) draws a gradient filled rectangle on your PHP GD images.
 * [**AgjGd::linebreaksfortext**](https://AgjGd.org/methods/linebreaksfortext/) automatically inserts line breaks into your text while using PHP’s `imagefttext`/`imagettftext` functions.

## Usage

### With Composer

This project offers support for the [Composer](https://getcomposer.org/) dependency manager. You can find the AgjGd package online on [packagist.org](https://packagist.org/packages/andrewgjohnson/agjgd).

#### Install using Composer

Either run this command:

```shell
composer require andrewgjohnson/agjgd
```

or add this to the `require` section of your composer.json file:

```json
"andrewgjohnson/agjgd": "2.*"
```

### Without Composer

To use without Composer add a [require_once](https://www.php.net/manual/en/function.require-once.php) call for the [`AgjGd.php` source file](https://raw.githubusercontent.com/andrewgjohnson/AgjGd/master/source/AndrewGJohnson/AgjGd.php).

```php
require_once 'source/AndrewGJohnson/AgjGd.php';
```

## Contributing

Please read our [contributing guidelines](https://github.com/andrewgjohnson/AgjGd/blob/master/.github/CONTRIBUTING.md) if you want to contribute.

You can contribute financially by becoming a [patron](https://patreon.com/agjopensource) at [patreon.com/agjopensource](https://patreon.com/agjopensource) to support AgjGd.

[![Patreon - Become a Patron](https://raster.shields.io/badge/Patreon%20-become%20a%20Patron-FD334A.png?style=for-the-badge&logo=patreon&logoColor=FD334A)](https://patreon.com/agjopensource)

## Help Requests

Please post any questions in the [discussions area](https://github.com/andrewgjohnson/AgjGd/discussions) of the GitHub repository.

If you discover a bug please enter an [issue](https://github.com/andrewgjohnson/AgjGd/issues) on the GitHub repository.

## Acknowledgements

Full list of contributors:
 * [Andrew G. Johnson (@andrewgjohnson)](https://github.com/andrewgjohnson)
 * [Philip van Heemstra (@vHeemstra)](https://github.com/vHeemstra)
 * [Imgbot (@ImgBotApp)](https://github.com/ImgBotApp)
 * [GitHub Actions (github-actions[bot])](https://github.com/features/actions)

Our [security policies and procedures](https://github.com/andrewgjohnson/AgjGd/blob/master/.github/SECURITY.md) come via the [atomist/samples](https://github.com/atomist/samples/blob/master/SECURITY.md) project. Our [pull request template](https://github.com/andrewgjohnson/AgjGd/blob/master/.github/PULL_REQUEST_TEMPLATE.md) comes via the [stevemao/github-issue-templates](https://github.com/stevemao/github-issue-templates) project. The [Jekyll theme](https://github.com/andrewgjohnson/open-source-documentation-jekyll-theme) was released by [Andrew G. Johnson](https://github.com/andrewgjohnson).

## Changelog

You can find all notable changes in the [changelog](https://github.com/andrewgjohnson/AgjGd/blob/master/CHANGELOG.md).
