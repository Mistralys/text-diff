# File Tree

```
text-diff/
├── changelog.md                   # Version history
├── composer.json                  # Composer package definition
├── phpunit.xml                    # PHPUnit configuration
├── README.md                      # Project documentation & usage examples
├── run-tests.bat                  # Windows shortcut to run PHPUnit
│
├── css/
│   └── styles.css                 # CSS for HTML diff highlighting (del/ins styles)
│
├── docs/
│   ├── run-phpstan.bat            # Windows shortcut to run PHPStan
│   ├── config/
│   │   └── phpstan.neon           # PHPStan configuration
│   └── phpstan/
│       └── _readme.txt
│
├── src/
│   ├── Diff.php                   # Core Diff class — entry point & diff algorithm
│   └── Diff/
│       ├── DiffException.php      # Custom exception class (extends BaseException)
│       ├── Renderer.php           # Abstract base renderer
│       ├── Styler.php             # CSS access utility for HTML highlighting
│       └── Renderer/
│           ├── HTML.php           # Renders diff as inline HTML (span/del/ins)
│           ├── HTMLTable.php      # Renders diff as a two-column HTML table
│           └── PlainText.php      # Renders diff as prefixed plain text
│
├── example/
│   ├── index.php                  # Live PHP example page (requires a web server)
│   ├── build.php                  # CLI build script — generates example/dist/index.html
│   ├── sample-original.txt        # Sample input file (original)
│   └── sample-modified.txt        # Sample input file (modified)
│   └── dist/
│       └── index.html             # Pre-built static HTML preview (committed to VCS)
│
└── tests/
    ├── bootstrap.php              # Test bootstrap (loads Composer autoloader)
    ├── assets/
    │   ├── classes/
    │   │   └── DiffTestCase.php   # Abstract base test case
    │   └── files/
    │       ├── string1.txt        # Test fixture file
    │       └── string2.txt        # Test fixture file
    └── testsuites/
        ├── Core/
        │   └── ParseTests.php     # Core diff parsing tests
        ├── Renderers/
        │   ├── HTMLTableTests.php # HTMLTable renderer tests
        │   ├── HTMLTests.php      # HTML renderer tests
        │   └── StringTests.php    # PlainText renderer tests
        └── Styler/
            └── StylerTests.php    # Styler utility tests
```

### Collapsed / Excluded

- `vendor/` — Composer-managed dependencies (auto-generated, not committed).
