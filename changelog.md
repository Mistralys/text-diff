### v2.0.2 - Corean characters fix
- Fixed `Diff::splitLines()` breaking corean characters ([#4](https://github.com/Mistralys/text-diff/issues/4)).

### v2.0.1 - Unicode fix
- Unicode characters are now correctly recognized.
- Added public `Diff::splitLines()` method.
- Added public `Diff::splitCharacters()` method.
- Switched to an MIT license.

### v2.0.0 - PHP 7.4 release
- Upgraded the code to 7.4 style.
- Loosened the `mistralys/application-utils` version constraint.
- Now including unit tests in PHPStan analysis.
- Fixed PHPStan recommendations.
- All classes now use strict typing.
- Added this changelog file.
- Upgraded unit tests with namespaces and expected naming scheme.

### v1.0.0 - Initial featureset release