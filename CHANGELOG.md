# Changelog

All notable changes to the [AgjGd project](https://github.com/andrewgjohnson/AgjGd) will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## [v2.0.0](https://github.com/andrewgjohnson/AgjGd/releases/tag/v2.0.0) (August 4, 2026)

AgjGd was a metapackage that simply required the six standalone projects. It is now the library itself: a single class,
`\AndrewGJohnson\AgjGd`, with the six projects as public static methods.

### Added
 * Added the `\AndrewGJohnson\AgjGd` class with a public static method for each of the six standalone functions it
   replaces, all still reachable by their original names:
   * `AgjGd::imageblendedcolorallocate()`
   * `AgjGd::imagecolorallocatefromstring()`
   * `AgjGd::imagegradientrectangle()`
   * `AgjGd::imagettftextblur()`
   * `AgjGd::imagettftextgradient()`
   * `AgjGd::linebreaks4imagettftext()`
 * Added `AgjGd::imagefttextfilter()` as the canonical name for the text-filtering method, since it can apply any GD
   filter to the text rather than only a blur. It has three aliases: `AgjGd::imagettftextfilter()` (the more common
   “ttf” spelling), `AgjGd::imagettftextblur()` and `AgjGd::imagefttextblur()` (which default the filter to a Gaussian
   blur). `AgjGd::imagettftextblur()` was the original standalone function’s name.
 * Added `AgjGd::imagefttextgradient()` as the canonical name for the gradient text method, with
   `AgjGd::imagettftextgradient()` (the original standalone function’s name, using the more common “ttf” spelling)
   retained as an alias.
 * Added `AgjGd::linebreaksfortext()` as the canonical name for the line-breaking method. It has five aliases:
   `AgjGd::linebreaks4imagettftext()` (the original standalone function’s name), `AgjGd::linebreaks4imagefttext()`,
   `AgjGd::linebreaks4text()`, `AgjGd::linebreaksforimagefttext()` and `AgjGd::linebreaksforimagettftext()`.
 * Added full PHP 8 type declarations to every parameter and return value.
 * Added [PHPStan](https://phpstan.org/) at its `max` level, covering the source, the tests and the examples.
 * Added a PHPUnit test suite per method (83 tests).
 * Added a `/methods/` section to [AgjGd.org](https://AgjGd.org/) documenting each method’s signature and parameters.
 * Added the examples from all six projects to [AgjGd.org/examples](https://AgjGd.org/examples/).

### Changed
 * **Breaking:** raised the minimum PHP version from 5.0 to 8.0.
 * **Breaking:** dropped the dependencies on the six standalone packages — AgjGd no longer installs them.
 * **Breaking:** AgjGd no longer defines the standalone projects’ functions. `imageblendedcolorallocate()`,
   `imagecolorallocatefromstring()`, `imagegradientrectangle()`, `imagettftextblur()`, `imagettftextgradient()` and
   both the `\AndrewGJohnson\AgjGd\` and `\andrewgjohnson\` flavours of `linebreaks4imagettftext()` now come from the
   six standalone packages, each of which ships a thin wrapper around this class. Install the package you need
   alongside AgjGd to carry on calling them.
 * **Breaking:** `imagecolorallocatefromstring()` now throws an `\InvalidArgumentException` rather than an `\Exception`
   for an invalid `$string` or `$alpha`. `\InvalidArgumentException` extends `\Exception`, so an existing
   `catch (\Exception $e)` still catches it.
 * `imagettftextblur()` and `imagettftextgradient()` accepted either an `$options` array or the next parameter in the
   ninth position, shifting the rest along, because PHP 8.0 added `$options` to `imagettftext()`. The class does not:
   `$options` is always the ninth parameter and every parameter means one thing. The wrappers in the two standalone
   packages still accept both styles.
 * `imagettftextblur()` and `imagettftextgradient()` no longer branch on `PHP_VERSION`, and no longer call
   `imagedestroy()`, neither of which PHP 8 needs.

### Fixed
 * `imagettftextgradient()` drew a gradient from `$color` to black when no gradient color was passed, rather than the
   solid `$color` its documentation promised. `AgjGd::imagettftextgradient()` now falls back to `imagettftext()` when
   `$gradientColor` is `NULL`.
 * `imagettftextgradient()` raised a `DivisionByZeroError` on text with no width or height, and could hand
   `imagecolorallocatealpha()` an out-of-range color component for an antialiased pixel that fell outside the text’s
   bounding box. The gradient position is now clamped and every color component is range-checked before allocation.
 * `imagettftextgradient()` read a color’s components by bit-shifting the color index, which is only correct for
   truecolor images. Both text methods now use `imagecolorsforindex()`, which is also correct for palette images, and
   both now handle the negative color index that turns off antialiasing.
 * `imagegradientrectangle()` threw an `\Exception` when `imageblendedcolorallocate()` could not be found. The two are
   now methods on the same class, so it cannot happen.

### Removed
 * Removed the reliance on `function_exists()` guards in the source. The class replaces them.

## [v1.0.5](https://github.com/andrewgjohnson/AgjGd/releases/tag/v1.0.5) (May 13, 2026)
 * Fixed corrupted apostrophe character in `composer.json` description
 * Fixed truncated description in `_config.yml` (added missing "library")
 * Fixed Patreon username from `agjgd` to `agjopensource` in `FUNDING.yml`
 * Fixed incorrect `@link` URL in `layout.html` docblock
 * Updated PHP documentation links from HTTP to HTTPS in `README.md`
 * Fixed grammatical errors and typos across various files
 * Removed defunct Google+ and Gitter references from `CODE_OF_CONDUCT.md`
 * Updated `.gitignore` to exclude `.phpunit.result.cache` and `.claude`

## [v1.0.4](https://github.com/andrewgjohnson/AgjGd/releases/tag/v1.0.4) (May 11, 2026)
 * Added .gitattributes
 * Updated Twitter to 𝕏.com
 * Tweaked layout to match the other AgjGd project documentation websites

## [v1.0.3](https://github.com/andrewgjohnson/AgjGd/releases/tag/v1.0.3) (June 1, 2024)
 * Added `/statistics/` page to [AgjGd.org](https://AgjGd.org/)
 * Updated documentation site layout
 * Added workflows for GitHub actions to handle badges and reports for downloads, stargazers, open issues and contributors

## [v1.0.2](https://github.com/andrewgjohnson/AgjGd/releases/tag/v1.0.2) (November 22, 2022)
 * Added `/menu/` page to [AgjGd.org](https://AgjGd.org/)
 * Signed up for [Patreon](https://patreon.com/agjopensource) and added links to README.md
 * Added `.github` folder to unclutter the root directory
 * Added `CODEOWNERS` file
 * Added `FUNDING.yml` file
 * Added `SECURITY.md` file
 * Added `SUPPORT.md` file
 * Updated shields.io badge aesthetics on README.md
 * Removed the MIT logo from the shields.io badge for AgjGd's license
 * Added Patrons shields.io badge to README.md
 * Removed all issue templates and disabled GitHub issues
 * Updated [avatar image](https://AgjGd.org/documentation/images/avatar.png)

## [v1.0.1](https://github.com/andrewgjohnson/AgjGd/releases/tag/v1.0.1) (November 17, 2022)
 * Moved all project URL's to AgjGd.org subdomains
 * Updated avatar

## [v1.0.0](https://github.com/andrewgjohnson/AgjGd/releases/tag/v1.0.0) (January 11, 2019)
 * Initial release of AgjGd
