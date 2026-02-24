# Key Data Flows

## 1. Compare Two Strings and Render as HTML

This is the most common usage path.

```
User code
  │
  ├─ Diff::compareStrings($str1, $str2)
  │    └─ new Diff($str1, $str2)          → stores strings internally
  │    └─ setCompareCharacters(false)      → default: line-by-line comparison
  │    └─ returns Diff instance
  │
  ├─ $diff->toHTML()
  │    └─ new HTML($this)                  → creates renderer with Diff reference
  │    └─ HTML::render()
  │         └─ $this->diff->toArray()      → runs the core LCS diff algorithm
  │         │    ├─ splitLines() or splitCharacters()  → tokenises input
  │         │    ├─ computeTable()          → builds LCS length matrix
  │         │    └─ generatePartialDiff()   → walks matrix to produce diff entries
  │         └─ Iterates diff array          → wraps each entry in <span>/<del>/<ins>
  │         └─ Returns HTML string inside <div class="text-diff-container">
  │
  └─ Diff::createStyler()->getStyleTag()
       └─ new Styler()                     → resolves css/styles.css path
       └─ Styler::getStyleTag()
            └─ FileHelper::readContents()  → reads CSS file
            └─ Returns <style>…</style> string
```

## 2. Compare Two Files

```
User code
  │
  └─ Diff::compareFiles($path1, $path2)
       └─ FileHelper::readContents($path1) → reads file 1
       └─ FileHelper::readContents($path2) → reads file 2
       └─ Diff::compareStrings(...)        → delegates to string comparison (see flow 1)
```

## 3. Render as Plain Text

```
$diff->toString()
  └─ new PlainText($this)
  └─ PlainText::render()
       └─ $this->diff->toArray()           → core diff algorithm
       └─ Iterates diff array              → prefixes each line with "  ", "- ", or "+ "
       └─ Returns plain text string
```

## 4. Render as HTML Table (Side-by-Side)

```
$diff->toHTMLTable()
  └─ new HTMLTable($this)
  └─ HTMLTable::render()
       └─ $this->diff->toArray()           → core diff algorithm
       └─ Groups consecutive same-type entries into left/right cells
       └─ Builds <table> with <tr> rows, each containing two <td> cells
       └─ Applies CSS classes: text-diff-del, text-diff-ins, text-diff-unmodified, text-diff-empty
       └─ Returns HTML table string
```

## 5. Memory Disposal

```
$diff->dispose()
  └─ Clears string1 and string2
  └─ Sets disposed = true
  └─ Any subsequent toArray() call throws DiffException (ERROR_DIFF_ALREADY_DISPOSED)
```
