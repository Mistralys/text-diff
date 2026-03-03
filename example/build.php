<?php

declare(strict_types=1);

/**
 * Build script: generates a static HTML preview of the example page.
 *
 * Run via:
 *   composer build
 *
 * Output: example/dist/index.html
 */

$distDir    = __DIR__ . '/dist';
$outputFile = $distDir . '/index.html';

// Capture the full HTML output of the example page.
ob_start();
require __DIR__ . '/index.php';
$html = (string)ob_get_clean();

if (!is_dir($distDir) && !mkdir($distDir, 0777, true)) {
    fwrite(STDERR, 'Error: could not create output directory: ' . $distDir . PHP_EOL);
    exit(1);
}

if (file_put_contents($outputFile, $html) === false) {
    fwrite(STDERR, 'Error: could not write output file: ' . $outputFile . PHP_EOL);
    exit(1);
}

echo 'Static preview written to: example/dist/index.html' . PHP_EOL;
