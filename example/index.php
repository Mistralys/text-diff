<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Mistralys\Diff\Diff;

// ─── Sample data ─────────────────────────────────────────────────────────────

$original = <<<'EOT'
The quick brown fox jumps over the lazy dog.
Pack my box with five dozen liquor jugs.
How vexingly quick daft zebras jump!
The five boxing wizards jump quickly.
Sphinx of black quartz, judge my vow.
EOT;

$modified = <<<'EOT'
The quick brown fox jumps over the lazy dog.
Pack my box with five dozen liquor jugs.
How vexingly quick daft zebras leap!
The five boxing wizards jump quickly.
Sphinx of black quartz, judge my oath.
Two of the five boxing wizards are absent today.
EOT;

$originalPhrase = 'The five boxing wizards jump quickly over the lazy river.';
$modifiedPhrase = 'The five climbing wizards jumped quickly across the lazy river.';

// ─── Renders (each needs its own Diff instance — toArray() is single-use) ────

$htmlInline      = Diff::compareStrings($original, $modified)->toHTML();
$htmlTable       = Diff::compareStrings($original, $modified)->toHTMLTable();
$plainText       = Diff::compareStrings($original, $modified)->toString();

$htmlInlineChars = Diff::compareStrings($originalPhrase, $modifiedPhrase, true)->toHTML();
$htmlTableChars  = Diff::compareStrings($originalPhrase, $modifiedPhrase, true)->toHTMLTable();

$fileHtmlInline  = Diff::compareFiles(
    __DIR__ . '/sample-original.txt',
    __DIR__ . '/sample-modified.txt'
)->toHTML();

$fileHtmlTable   = Diff::compareFiles(
    __DIR__ . '/sample-original.txt',
    __DIR__ . '/sample-modified.txt'
)->toHTMLTable();

// ─── Styling ──────────────────────────────────────────────────────────────────

$styler   = Diff::createStyler();
$styleTag = $styler->getStyleTag();   // Inline <style> block from css/styles.css

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mistralys/text-diff — Examples</title>

    <?= $styleTag ?>

    <style>
        /* ── Page chrome ─────────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
            font-size: 15px;
            line-height: 1.6;
            margin: 0;
            padding: 0 0 60px;
            background: #f0f2f5;
            color: #222;
        }

        header {
            background: #1a1a2e;
            color: #e0e0e0;
            padding: 28px 40px;
            margin-bottom: 36px;
        }
        header h1 { margin: 0 0 4px; font-size: 1.6rem; color: #fff; }
        header p  { margin: 0; font-size: 0.9rem; color: #aaa; }

        main { max-width: 1100px; margin: 0 auto; padding: 0 24px; }

        /* ── Section cards ───────────────────────────────────────────────── */
        .section {
            background: #fff;
            border: 1px solid #dde1e7;
            border-radius: 8px;
            margin-bottom: 36px;
            overflow: hidden;
        }

        .section-header {
            background: #f7f8fa;
            border-bottom: 1px solid #dde1e7;
            padding: 14px 24px;
            display: flex;
            align-items: baseline;
            gap: 14px;
        }
        .section-header h2 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
            color: #1a1a2e;
        }
        .badge {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            padding: 2px 8px;
            border-radius: 20px;
        }
        .badge-html   { background: #dbeafe; color: #1e40af; }
        .badge-table  { background: #dcfce7; color: #166534; }
        .badge-plain  { background: #fef9c3; color: #854d0e; }
        .badge-chars  { background: #ede9fe; color: #5b21b6; }
        .badge-files  { background: #ffe4e6; color: #9f1239; }

        .section-body { padding: 24px; }

        /* ── Input labels ────────────────────────────────────────────────── */
        .inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }
        .inputs label {
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #555;
            display: block;
            margin-bottom: 6px;
        }
        .inputs pre {
            margin: 0;
            background: #f8f9fa;
            border: 1px solid #e2e5ea;
            border-radius: 5px;
            padding: 10px 14px;
            font-size: 0.85rem;
            white-space: pre-wrap;
            word-break: break-word;
        }

        /* ── Result area ─────────────────────────────────────────────────── */
        .result-label {
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #555;
            margin-bottom: 8px;
        }

        .plain-output pre {
            margin: 0;
            background: #1e1e2e;
            color: #cdd6f4;
            border-radius: 5px;
            padding: 16px 20px;
            font-size: 0.875rem;
            line-height: 1.7;
            white-space: pre-wrap;
            word-break: break-word;
        }

        /* ── HTMLTable renderer tweaks ───────────────────────────────────── */
        .text-diff-table-wrap { overflow-x: auto; }

        table.text-diff {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }
        table.text-diff td {
            padding: 6px 12px;
            vertical-align: top;
            border: 1px solid #e5e7eb;
            width: 50%;
        }
        table.text-diff .text-diff-del { background: #fff1f2; }
        table.text-diff .text-diff-ins { background: #f0fdf4; }
        table.text-diff .text-diff-unmodified { background: #fff; }
        table.text-diff .text-diff-empty { background: #f9fafb; }

        /* ── Styler API block ────────────────────────────────────────────── */
        .api-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }
        .api-card {
            border: 1px solid #e2e5ea;
            border-radius: 6px;
            overflow: hidden;
        }
        .api-card-header {
            background: #f7f8fa;
            border-bottom: 1px solid #e2e5ea;
            padding: 8px 14px;
            font-size: 0.78rem;
            font-weight: 700;
            font-family: monospace;
            color: #374151;
        }
        .api-card-body {
            padding: 12px 14px;
            font-size: 0.82rem;
            word-break: break-all;
            color: #444;
        }
        .api-card-body code {
            display: block;
            background: #f0f2f5;
            border-radius: 4px;
            padding: 8px 10px;
            font-size: 0.8rem;
            white-space: pre-wrap;
        }

        @media (max-width: 640px) {
            .inputs { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<header>
    <h1>mistralys/text-diff</h1>
    <p>Showcasing all renderers, comparison modes, and styling options provided by the library.</p>
</header>

<main>

    <!-- ════════════════════════════════════════════════════════════════════ -->
    <!--  1. Inline HTML — Line-by-line                                      -->
    <!-- ════════════════════════════════════════════════════════════════════ -->
    <div class="section">
        <div class="section-header">
            <h2>Inline HTML diff &mdash; line-by-line</h2>
            <span class="badge badge-html">toHTML()</span>
        </div>
        <div class="section-body">
            <div class="inputs">
                <div>
                    <label>Original</label>
                    <pre><?= htmlspecialchars($original) ?></pre>
                </div>
                <div>
                    <label>Modified</label>
                    <pre><?= htmlspecialchars($modified) ?></pre>
                </div>
            </div>
            <div class="result-label">Result</div>
            <?= $htmlInline ?>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════════════════ -->
    <!--  2. HTML Table — Line-by-line                                       -->
    <!-- ════════════════════════════════════════════════════════════════════ -->
    <div class="section">
        <div class="section-header">
            <h2>Side-by-side HTML table &mdash; line-by-line</h2>
            <span class="badge badge-table">toHTMLTable()</span>
        </div>
        <div class="section-body">
            <div class="result-label">Result</div>
            <div class="text-diff-table-wrap">
                <?= $htmlTable ?>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════════════════ -->
    <!--  3. Plain Text — Line-by-line                                       -->
    <!-- ════════════════════════════════════════════════════════════════════ -->
    <div class="section">
        <div class="section-header">
            <h2>Plain text diff &mdash; line-by-line</h2>
            <span class="badge badge-plain">toString()</span>
        </div>
        <div class="section-body">
            <div class="result-label">Result — prefixes: &nbsp;<code>  </code> unmodified &nbsp;|&nbsp; <code>- </code> deleted &nbsp;|&nbsp; <code>+ </code> inserted</div>
            <div class="plain-output">
                <pre><?= htmlspecialchars($plainText) ?></pre>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════════════════ -->
    <!--  4. Inline HTML — Character-by-character                            -->
    <!-- ════════════════════════════════════════════════════════════════════ -->
    <div class="section">
        <div class="section-header">
            <h2>Inline HTML diff &mdash; character-by-character</h2>
            <span class="badge badge-chars">compareCharacters = true</span>
        </div>
        <div class="section-body">
            <div class="inputs">
                <div>
                    <label>Original phrase</label>
                    <pre><?= htmlspecialchars($originalPhrase) ?></pre>
                </div>
                <div>
                    <label>Modified phrase</label>
                    <pre><?= htmlspecialchars($modifiedPhrase) ?></pre>
                </div>
            </div>
            <div class="result-label">Result — <em>toHTML()</em></div>
            <?= $htmlInlineChars ?>
            <div class="result-label" style="margin-top:20px;">Result — <em>toHTMLTable()</em></div>
            <div class="text-diff-table-wrap">
                <?= $htmlTableChars ?>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════════════════ -->
    <!--  5. File Comparison                                                  -->
    <!-- ════════════════════════════════════════════════════════════════════ -->
    <div class="section">
        <div class="section-header">
            <h2>File comparison</h2>
            <span class="badge badge-files">compareFiles()</span>
        </div>
        <div class="section-body">
            <div class="inputs">
                <div>
                    <label>sample-original.txt</label>
                    <pre><?= htmlspecialchars(file_get_contents(__DIR__ . '/sample-original.txt') ?: '') ?></pre>
                </div>
                <div>
                    <label>sample-modified.txt</label>
                    <pre><?= htmlspecialchars(file_get_contents(__DIR__ . '/sample-modified.txt') ?: '') ?></pre>
                </div>
            </div>
            <div class="result-label">Inline HTML result</div>
            <?= $fileHtmlInline ?>
            <div class="result-label" style="margin-top:20px;">Side-by-side table result</div>
            <div class="text-diff-table-wrap">
                <?= $fileHtmlTable ?>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════════════════ -->
    <!--  6. Styler API                                                       -->
    <!-- ════════════════════════════════════════════════════════════════════ -->
    <div class="section">
        <div class="section-header">
            <h2>Styler API</h2>
            <span class="badge badge-html">Diff::createStyler()</span>
        </div>
        <div class="section-body">
            <p style="margin-top:0; color:#555;">
                The <code>Styler</code> class gives you several ways to include the bundled CSS in your pages.
                Pass your <code>vendor/</code> folder URL to <code>getStylesheetURL()</code> /
                <code>getStylesheetTag()</code> when you want to link the file rather than inline it.
            </p>
            <div class="api-grid">
                <div class="api-card">
                    <div class="api-card-header">getStyleTag()</div>
                    <div class="api-card-body">
                        Returns the full CSS wrapped in a <code>&lt;style&gt;</code> tag. Use this in the <code>&lt;head&gt;</code> of your page for self-contained output.<br><br>
                        <code><?= htmlspecialchars(
                            mb_substr($styler->getStyleTag(), 0, 120) . '…'
                        ) ?></code>
                    </div>
                </div>
                <div class="api-card">
                    <div class="api-card-header">getCSS()</div>
                    <div class="api-card-body">
                        Returns the raw CSS string — useful when you manage your own stylesheet bundle.<br><br>
                        <code><?= htmlspecialchars(
                            mb_substr($styler->getCSS(), 0, 120) . '…'
                        ) ?></code>
                    </div>
                </div>
                <div class="api-card">
                    <div class="api-card-header">getStylesheetPath()</div>
                    <div class="api-card-body">
                        Returns the absolute filesystem path to the bundled <code>css/styles.css</code> file.<br><br>
                        <code><?= htmlspecialchars($styler->getStylesheetPath()) ?></code>
                    </div>
                </div>
                <div class="api-card">
                    <div class="api-card-header">getStylesheetURL( $vendorURL )</div>
                    <div class="api-card-body">
                        Builds a browser-accessible URL to the stylesheet. Pass the URL of your <code>vendor/</code> folder.<br><br>
                        <code>$styler->getStylesheetURL('/vendor')</code>
                        &nbsp;&rarr;&nbsp;
                        <code><?= htmlspecialchars($styler->getStylesheetURL('/vendor')) ?></code>
                    </div>
                </div>
                <div class="api-card">
                    <div class="api-card-header">getStylesheetTag( $vendorURL )</div>
                    <div class="api-card-body">
                        Returns a <code>&lt;link rel="stylesheet"&gt;</code> tag pointing to the bundled CSS.<br><br>
                        <code>$styler->getStylesheetTag('/vendor')</code>
                        &nbsp;&rarr;&nbsp;
                        <code><?= htmlspecialchars($styler->getStylesheetTag('/vendor')) ?></code>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

</body>
</html>
