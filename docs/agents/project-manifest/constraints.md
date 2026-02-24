# Constraints & Conventions

## Strict Typing

All source files declare `declare(strict_types=1);` at the top. This must be maintained in any new files.

## Namespace Discrepancy

The renderers use namespace `Mistrals\Diff\Renderer` (note: **Mistrals**, not **Mistralys**). This differs from the main `Mistralys\Diff` namespace used by `Diff`, `DiffException`, and the `Styler`. This appears to be an established convention in the codebase and must not be "corrected" without the author's intent, as it would be a breaking change for consumers.

## Autoloading

Composer uses **classmap** autoloading (not PSR-4). All classes in `src/` are discovered by scanning the directory. This means file/directory names do not need to match namespace segments.

## Single-Use `toArray()`

`Diff::toArray()` can only be called once per instance. It clears the internal sequences after execution to free memory. Calling `dispose()` before `toArray()` throws `DiffException`. The convenience render methods (`toString`, `toHTML`, `toHTMLTable`) each call `toArray()` internally, so only one render method can be called per `Diff` instance.

## File I/O via `application-utils`

All file reading is performed through `AppUtils\FileHelper::readContents()`, not native PHP functions. This provides standardised error handling via `FileHelper_Exception`.

## Exception Hierarchy

`DiffException` extends `AppUtils\BaseException`. All library-specific errors use numeric error codes defined as class constants (e.g., `ERROR_DIFF_ALREADY_DISPOSED = 66901`).

## CSS Naming

HTML renderers wrap output in a container with class `text-diff-container`. The HTMLTable renderer uses CSS classes `text-diff-del`, `text-diff-ins`, `text-diff-unmodified`, and `text-diff-empty` on table cells. The bundled stylesheet (`css/styles.css`) provides styling for these classes.

## Comparison Modes

Two comparison modes are supported:
- **Line-by-line** (default) — splits input using Unicode-aware regex `\R`.
- **Character-by-character** — splits input using `mb_str_split()`.

Both splitting methods throw `DiffException` on failure.

## Test Structure

- Test base class: `DiffUnitTests\DiffTestCase` (extends PHPUnit `TestCase`).
- Test fixtures: `tests/assets/files/`.
- PHPUnit configuration defines two test suites: *Core functionality tests* and *Rendering tests*.
- The `Styler/` test suite directory exists but is not registered in `phpunit.xml`.

## Fluent Interfaces

Setter methods on `Diff`, renderer classes, and `Styler` return `$this` (or the class type) to enable method chaining.
