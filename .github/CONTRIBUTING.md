# Contributing Guidelines

## Contribute Code

### Coding Conventions

Please be consistent with what already exists. New code should not produce any new errors/warnings when the commands below are run. New code that produces new errors/warnings may be rejected.

Run the following command to check your changes against our linters, static analysis and unit tests:

```shell
composer test
```

#### PHP

The project uses the [PHP_CodeSniffer](https://github.com/squizlabs/php_codesniffer) linter tool to enforce coding standards in the [PHP source](https://AgjGd.org/source/php/), [unit tests](https://AgjGd.org/source/phpunit-test-suite/) and [examples](https://AgjGd.org/examples/). The project uses the PHP_CodeSniffer [PSR-12](https://www.php-fig.org/psr/psr-12/) ruleset defined in the [PHP_CodeSniffer configuration file](https://github.com/andrewgjohnson/AgjGd/blob/master/phpcs.xml.dist). Run this command to test all code changes:

```shell
composer lint
```

Alternatively, run this command to use phpcbf to fix rule violations:

```shell
composer lint:fix
```

### Static Analysis

The project uses [PHPStan](https://phpstan.org/) to statically analyse the code at its strictest [`max` level](https://phpstan.org/user-guide/rule-levels). The configuration is defined in the [PHPStan configuration file](https://github.com/andrewgjohnson/AgjGd/blob/master/phpstan.neon.dist). A second pass ([`phpstan-php80.neon.dist`](https://github.com/andrewgjohnson/AgjGd/blob/master/phpstan-php80.neon.dist)) holds the shipped code to the minimum supported PHP version (8.0) since the unit tests only run on PHP 8.3 and newer. Run this command to run both passes:

```shell
composer phpstan
```

### Unit Tests

The project uses [PHPUnit](https://phpunit.de/) framework to run unit tests. The tests are all located in the [`tests` folder](https://github.com/andrewgjohnson/AgjGd/tree/master/tests). All tests should continue to pass and all new features should ideally include unit tests. Run this command to execute all unit tests:

```shell
composer phpunit
```

### Code Coverage

The unit tests cover 100% of the [PHP source](https://AgjGd.org/source/php/) and new code should keep it that way. Run this command to run the unit tests and print a code coverage summary to the terminal:

```shell
composer coverage
```

Alternatively, run this command to generate a browsable HTML coverage report in the `coverage` folder:

```shell
composer coverage:html
```

Both commands require a coverage driver such as [Xdebug](https://xdebug.org/) or [PCOV](https://github.com/krakjoe/pcov) to be installed and enabled.

### Online Documentation

The project’s online documentation is available at [AgjGd.org](https://AgjGd.org/). Please ensure the documentation is updated along with any code changes. All of the files used to generate the documentation are in the [`documentation` folder](https://github.com/andrewgjohnson/AgjGd/tree/master/documentation/). [The website](https://AgjGd.org/) is powered by [GitHub Pages](https://pages.github.com/) which uses [Jekyll](https://jekyllrb.com/). Run this command to test the online documentation website locally if you have Jekyll installed:

```shell
jekyll serve
```

If you are on Ruby 4.0 or later you may encounter a gem conflict between `jekyll-remote-theme` and `jekyll-sass-converter`. If so, first create a link inside the project root pointing to the theme repository's layouts (this only needs to be done once).

On Windows:

```shell
mklink /J _layouts "..\open-source-documentation-jekyll-theme\_layouts"
mklink /J assets "..\open-source-documentation-jekyll-theme\assets"
```

On macOS or Linux:

```shell
ln -s ../open-source-documentation-jekyll-theme/_layouts _layouts
ln -s ../open-source-documentation-jekyll-theme/assets assets
```

Then create a `_config.local.yml` file in the project root containing:

```yaml
plugins:
  - jekyll-redirect-from
```

Then run Jekyll with both config files instead:

```shell
jekyll serve --config _config.yml,_config.local.yml
```

### Submitting Changes

Please send a [GitHub pull request](https://github.com/andrewgjohnson/AgjGd/pull/new/master) with a clear list of what you’ve done (read more about [pull requests](https://docs.github.com/en/pull-requests/collaborating-with-pull-requests/proposing-changes-to-your-work-with-pull-requests/about-pull-requests)). Please follow our coding conventions (above) and make sure all of your commits are atomic (one feature per commit). Please use our [pull request template](https://github.com/andrewgjohnson/AgjGd/blob/master/.github/PULL_REQUEST_TEMPLATE.md) when submitting pull requests.

Always write a clear log message for your commits. One-line messages are fine for small changes, but bigger changes should look like this:

```shell
$ git commit -m "A brief summary of the commit
>
> A paragraph describing what changed and its impact."
```

## Contribute Financially

You can contribute financially by becoming a [patron](https://patreon.com/agjopensource) at [patreon.com/agjopensource](https://patreon.com/agjopensource) to support AgjGd.

[![Patreon - Become a Patron](https://raster.shields.io/badge/Patreon%20-become%20a%20Patron-FD334A.png?style=for-the-badge&logo=patreon&logoColor=FD334A)](https://patreon.com/agjopensource)

## Code of Conduct

In order to participate, your behaviour must conform to our [code of conduct](https://github.com/andrewgjohnson/AgjGd/blob/master/.github/CODE_OF_CONDUCT.md).
