<?php

declare(strict_types=1);

namespace DiffUnitTests\Styler;

use DiffUnitTests\DiffTestCase;
use Mistralys\Diff\Diff;
use AppUtils\FileHelper;

final class StylerTests extends DiffTestCase
{
    public function test_getPath() : void
    {
        $styler = Diff::createStyler();

        $path = $styler->getStylesheetPath();

        $this->assertEquals(
            FileHelper::normalizePath($path),
            FileHelper::normalizePath(__DIR__.'/../../../css/styles.css')
        );
    }

    public function test_getCSS() : void
    {
        $styler = Diff::createStyler();

        $css = $styler->getCSS();

        $this->assertEquals($css, file_get_contents($styler->getStylesheetPath()));
    }
}
