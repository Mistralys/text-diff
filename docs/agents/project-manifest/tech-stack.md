# Tech Stack & Patterns

## Runtime & Language

- **Language:** PHP 7.4+ (strict typing enabled in all source files)
- **Type:** Composer library (package type `library`)

## Package Identity

- **Composer name:** `mistralys/text-diff`
- **License:** MIT
- **Current version:** 2.0.2

## Dependencies

### Runtime

| Package | Constraint | Purpose |
|---|---|---|
| `php` | `>=7.4` | Minimum PHP version |
| `mistralys/application-utils` | `>=1.2.5` | Provides `FileHelper` (file I/O) and `BaseException` (exception base class) |

### Development

| Package | Constraint | Purpose |
|---|---|---|
| `phpunit/phpunit` | `>=9.6` | Unit testing |
| `phpstan/phpstan` | `>=1.9` | Static analysis |

## Architectural Patterns

- **Static factory methods** — `Diff::compareStrings()` and `Diff::compareFiles()` are the primary entry points; they return a `Diff` instance.
- **Strategy / Renderer pattern** — Output formatting is delegated to `Renderer` subclasses (`PlainText`, `HTML`, `HTMLTable`). The `Diff` convenience methods (`toString`, `toHTML`, `toHTMLTable`) instantiate the appropriate renderer internally.
- **Disposable pattern** — `Diff::dispose()` allows early release of stored strings to free memory.
- **Classmap autoloading** — Composer autoloads via `classmap` on the `src/` directory (not PSR-4).

## Build & Test Tooling

- **Test runner:** PHPUnit, configured via `phpunit.xml`. Bootstrap file: `tests/bootstrap.php`.
- **Static analysis:** PHPStan, configured at `docs/config/phpstan.neon`, run via `docs/run-phpstan.bat`.
- **Test shortcut:** `run-tests.bat` at project root.
- **Static example build:** `composer build` runs `example/build.php`, which captures the output of `example/index.php` and writes it to `example/dist/index.html`. The generated file is committed to VCS as a no-server preview.
- **Stability:** `minimum-stability: dev`, `prefer-stable: true`.
