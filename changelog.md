# Text Diff Changelog

## v2.1.0 - Dependency Update (Breaking-S)
- Docs: Added some functional examples.
- Composer: Updated application-utils minimum to v3.2.0, removing the GeShi security advisory ([#8](https://github.com/Mistralys/text-diff/issues/8)).
- Composer: Raised minimum PHP version to 8.4.
- Composer: Raised minimum PHPUnit dev requirement to 12.0.
- License: Added the MIT LICENSE file to the repository ([#2](https://github.com/Mistralys/text-diff/issues/2)).
- Docs: Added agentic coding support with AGENTS.md and project manifest.
- Code: PHPStan clean up to level 9.

### Breaking Changes

The minimum required PHP version has been raised from 7.4 to 8.4. 
Update your PHP runtime to 8.4 or higher before upgrading this package.

## v2.0.2 - Corean characters fix
- Fixed `Diff::splitLines()` breaking corean characters ([#4](https://github.com/Mistralys/text-diff/issues/4)).

## v2.0.1 - Unicode fix
- Unicode characters are now correctly recognized.
- Added public `Diff::splitLines()` method.
- Added public `Diff::splitCharacters()` method.
- Switched to an MIT license.

## v2.0.0 - PHP 7.4 release
- Upgraded the code to 7.4 style.
- Loosened the `mistralys/application-utils` version constraint.
- Now including unit tests in PHPStan analysis.
- Fixed PHPStan recommendations.
- All classes now use strict typing.
- Added this changelog file.
- Upgraded unit tests with namespaces and expected naming scheme.

## v1.0.0 - Initial featureset release