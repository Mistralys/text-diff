# DIFF string comparison for PHP

Class used to compare differences between two strings using a DIFF 
implementation, with the possibility to render the diff to HTML 
with highlighting.

## Requirements

- PHP 8.4+
- [Composer](https://getcomposer.org)

## Installation

Require via composer:

```
composer require mistralys/text-diff
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

> **Important:** Each `Diff` instance is single-use. All render methods
> (`toString`, `toHTML`, `toHTMLTable`, `toArray`) call the internal diff
> engine which clears itself after the first run. Only call **one** render
> method per instance; create a new instance for each render.

```php
use Mistralys\Diff\Diff;

// Each call gets its own instance:
$string = Diff::compareFiles('/path/to/file1', '/path/to/file2')->toString();
$html   = Diff::compareFiles('/path/to/file1', '/path/to/file2')->toHTML();
$table  = Diff::compareFiles('/path/to/file1', '/path/to/file2')->toHTMLTable();
$array  = Diff::compareFiles('/path/to/file1', '/path/to/file2')->toArray();
```

### Changing the comparison mode

By default, the comparison will be made per line. It can be changed
to be done on a per-character basis.

Pass `true` as the third argument to the factory method:

```php
use Mistralys\Diff\Diff;

$diff = Diff::compareFiles('/path/to/file1', '/path/to/file2', true);
```

Or use the fluent setter on an existing instance:

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

$css  = $styler->getCSS();                          // raw CSS string
$tag  = $styler->getStyleTag();                     // CSS wrapped in a <style> tag
$path = $styler->getStylesheetPath();               // absolute path to the stylesheet file
$url  = $styler->getStylesheetURL('/vendor');       // URL to the file, given the vendor folder URL
$link = $styler->getStylesheetTag('/vendor');       // a full <link rel="stylesheet"> tag
```

For example, to show a highlighted diff with inline styles:

```php
use Mistralys\Diff\Diff;

$diff = Diff::compareStrings('String 1', 'String 2');

echo Diff::createStyler()->getStyleTag();
echo $diff->toHTML();
```

### Releasing diff instances

When a `Diff` instance is no longer needed and you want to free its memory
explicitly, call `dispose()`:

```php
use Mistralys\Diff\Diff;

$diff = Diff::compareStrings('String 1', 'String 2');
$html = $diff->toHTML();

$diff->dispose();
```

> **Note:** Calling any render method after `dispose()` will throw a
> `DiffException`. Always render before disposing.

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