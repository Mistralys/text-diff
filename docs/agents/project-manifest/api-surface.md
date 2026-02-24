# Public API Surface

## Namespace `Mistralys\Diff`

### Class `Diff`

Main entry point. Compares two strings and produces diff output in various formats.

#### Constants

| Constant | Value | Description |
|---|---|---|
| `UNMODIFIED` | `0` | Line/character exists in both strings |
| `DELETED` | `1` | Line/character only in the first string |
| `INSERTED` | `2` | Line/character only in the second string |
| `ERROR_DIFF_ALREADY_DISPOSED` | `66901` | Error code: diff used after disposal |
| `ERROR_CANNOT_SPLIT_STRING` | `66902` | Error code: string splitting failed |

#### Constructor

```php
public function __construct(string $string1, string $string2)
```

#### Static Factory Methods

```php
public static function compareStrings(string $string1, string $string2, bool $compareCharacters = false): Diff
public static function compareFiles(string $file1, string $file2, bool $compareCharacters = false): Diff
public static function createStyler(): Styler
```

#### Instance Methods

```php
public function setCompareCharacters(bool $compare = true): Diff
public function toArray(): array  // @return array<int, array<int, int|string>>
public function toString(string $separator = "\n"): string
public function toHTML(string $separator = '<br>'): string
public function toHTMLTable(string $indentation = '', string $separator = '<br>'): string
public function dispose(): Diff
```

#### Static Utility Methods

```php
public static function splitLines(string $string): array    // @return string[]
public static function splitCharacters(string $string): array // @return string[]
```

---

### Class `DiffException`

Custom exception class for the library.

**Extends:** `AppUtils\BaseException`

No additional public members.

---

## Namespace `Mistrals\Diff\Renderer`

> **Note:** The renderer namespace is `Mistrals` (not `Mistralys`) — this is intentional in the codebase.

### Abstract Class `Renderer`

Base class for all output renderers.

#### Constructor

```php
public function __construct(Diff $diff)
```

#### Abstract Methods

```php
abstract public function render(); // @return mixed
```

---

### Class `PlainText` extends `Renderer`

Renders diff as plain text with `  ` (unmodified), `- ` (deleted), `+ ` (inserted) prefixes.

#### Methods

```php
public function setSeparator(string $separator): PlainText
public function getSeparator(): string
public function render(): string
```

---

### Class `HTML` extends `Renderer`

Renders diff as inline HTML using `<span>`, `<del>`, and `<ins>` elements, wrapped in a `<div class="text-diff-container">`.

#### Methods

```php
public function setSeparator(string $separator): HTML
public function getSeparator(): string
public function getContainer(): string
public function render(): string
```

---

### Class `HTMLTable` extends `Renderer`

Renders diff as a two-column HTML `<table>` with side-by-side comparison.

#### Methods

```php
public function setTab(string $tab): HTMLTable
public function getTab(): string
public function getNewlineCharacter(): string
public function setSeparator(string $separator): HTMLTable
public function setIndentation(string $indent): HTMLTable
public function getSeparator(): string
public function getIndentation(): string
public function render(): string
```

---

## Namespace `Mistralys\Diff\Styler`

### Class `Styler`

Utility class providing access to the CSS styles used for HTML diff highlighting.

#### Constants

| Constant | Value | Description |
|---|---|---|
| `ERROR_CSS_FILE_NOT_FOUND` | `66801` | Error code: CSS file missing |

#### Constructor

```php
public function __construct()  // @throws DiffException
```

#### Methods

```php
public function getCSS(): string
public function getStyleTag(): string
public function getStylesheetPath(): string
public function getStylesheetURL(string $vendorURL): string
public function getStylesheetTag(string $vendorURL): string
```
