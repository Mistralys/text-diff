<?php

use Mistralys\Diff\Diff;
use AppUtils\FileHelper;

final class Diff_Styler_StylerTest extends DiffTestCase
{
    public function test_getPath()
    {
        $styler = Diff::createStyler();
        
        $path = $styler->getStylesheetPath();
        
        $this->assertEquals(
            FileHelper::normalizePath($path), 
            FileHelper::normalizePath(realpath('../../../css/styles.css'))
        );
    }
    
    public function test_getCSS()
    {
        $styler = Diff::createStyler();
        
        $css = $styler->getCSS();
        
        $this->assertEquals($css, file_get_contents($styler->getStylesheetPath()));
    }
}
