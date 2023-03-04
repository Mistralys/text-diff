[![Build Status](https://travis-ci.com/Mistralys/text-diff.svg?branch=master)](https://travis-ci.com/Mistralys/text-diff) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Mistralys/text-diff/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Mistralys/text-diff/?branch=master)

# DIFF string comparison for PHP

Class used to compare differences between two strings using a DIFF 
implementation, with the possibility to render the diff to HTML 
with highlighting.

## Requirements

- PHP 7.4+
- [Composer](https://getcomposer.org)

## Installation

Require via composer:

```
require mistralys/text-diff
```

Or clone it locally via GIT, or download any of the 
[available releases](https://github.com/Mistralys/text-diff/releases).

## Usage

### Comparing strings

```php
use Mistralys\Diff\Diff;

$diff = Diff::compareStrings('String 1', 'String 2');
```

### Comparing files

```php
use Mistralys\Diff\Diff;

$diff = Diff::compareFiles('/path/to/file1', '/path/to/file2');
```

Once the diff instance has been created, choose any of the `toXXX`
methods to retrieve the diff in your preferred format.

```php
use Mistralys\Diff\Diff;

$diff = Diff::compareFiles('/path/to/file1', '/path/to/file2');

$string = $diff->toString();
$html = $diff->toHTML();
$table = $diff->toHTMLTable();
$array = $diff->toArray();
```

### Changing the comparison mode

By default, the comparison will be made per line. It can be changed
to be done on a per-character basis:

```php
use Mistralys\Diff\Diff;

$diff = Diff::compareFiles('/path/to/file1', '/path/to/file2');

$diff->setCompareCharacters(true);
```

### HTML Highlighting

The `toHTML` and `toHTMLTable` methods support highlighting the 
changes with the integrated CSS styles. To insert these, use the 
`Styler` class: it offers several ways to access the CSS.

```php
use Mistralys\Diff\Diff;

$diff = Diff::compareFiles('/path/to/file1', '/path/to/file2');
$styler = Diff::createStyler();
```

From here, use any of the styler's methods according to your project's needs.

```php
use Mistralys\Diff\Diff;

$styler = Diff::createStyler();

$css = $styler->getCSS(); // get the raw CSS styles
$tag = $styler->getStyleTag(); // CSS styles with a <style> tag
$path = $styler->getStylesheetPath(); // absolute path to the file
$url = $styler->getStylesheetURL('/vendor'); // URL to the file, given the vendor folder URL
```

For example, to show a highlighted diff with inline styles:

```php
use Mistralys\Diff\Diff;

$diff = Diff::compareStrings('String 1', 'String 2');

echo Diff::createStyler()->getStyleTag();
echo $diff->toHTML();
```

## Credits

The original Diff class was developed by Kate Morley. Compared to her 
version, this has been reworked extensively. The core mechanism stays 
the same, but updated for PHP7, and split up into subclasses to make 
it easier to extend and maintain. The static comparison methods are 
still there, but they return a diff instance now.

The original project homepage can be found here:

http://code.iamkate.com/php/diff-implementation/

> Kate has since removed the library from her site, but I am keeping
  this here as reference.